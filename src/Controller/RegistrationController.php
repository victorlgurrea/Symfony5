<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/registro", name="app_register", priority = 4)
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setActivo(false);
            $user->setRoles(['ROLE_USER']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('BotSubastas@subastas.com', 'Subastas Mail Bot'))
                    ->to($user->getEmail())
                    ->subject('Confirma tu correo')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            
            $this->addFlash('success', "Usuario registrado correctamente, revise su correo");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Tu email ha sido verificado.');

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/terminos-de-uso", name="app_terminos", priority = 5)
     */
    public function terminos(): Response
    {
        return $this->render('registration/use_terms.html.twig', []);
    }

    /**
    * @Route("/terminos-de-uso", name="app_activar_usuario", priority = 6)
    */
    public function activarCuentaUsuario(string $token, UserRepository $userRepository): Response
    {
        $datosToken = array(json_decode($token));
        $fechaActual = new \DateTime();
        $fechaExpiracion = new \DateTime($datosToken['fechaExpiracion']);

        $idUsuario = $datosToken['id'];
        if($fechaActual > $fechaExpiracion){
            throw $this->createNotFoundException();
        }

        $usuario = $userRepository->findOneBy($idUsuario);
        $usuario->setActivo(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush($usuario);
        $this->addFlash("success", "Usuario activado correctamente!");
        return $this->redirectToRoute('app_login');
    }

}
