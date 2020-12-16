<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Services\Mailer;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Component\Validator\Constraints\DateTime;

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
    public function register(Request $request, Mailer $mailer,UserPasswordEncoderInterface $passwordEncoder): Response
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

            $mailer->email_registro_usuario($user);
            
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
    * @Route("/registro/activar-usuario/{token}", name="app_activar_usuario",priority = 6)
    */
    public function activarCuentaUsuario(string $token, UserRepository $userRepository, Encryptor $encryptor ): Response
    {
        $tokenJson = $encryptor->decrypt($token);
        $datosToken = (array)json_decode($tokenJson);
        $fechaActual = new DateTime();
        $fechaExpiracion = new DateTime($datosToken['fechaExpiracion']);

        $idUsuario = $datosToken['id'];
        if($fechaActual < $fechaExpiracion){
            throw $this->createNotFoundException();
        }

        $usuario = $userRepository->findOneById($idUsuario);
        $usuario->setActivo(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush($usuario);
        $this->addFlash("success", "Usuario activado correctamente!");
        return $this->redirectToRoute('app_login');
    }

}
