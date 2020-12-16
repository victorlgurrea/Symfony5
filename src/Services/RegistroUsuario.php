<?php
namespace App\Services;
use App\Entity\User;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class RegistroUsuario
{
    private $router;
    private $encryptor;

    public function __construct(Encryptor $encryptor, UrlGeneratorInterface $router){
        $this->router = $router;
        $this->encryptor = $encryptor;
    }

    public function generarUrlActivacionUsuario(User $user){
        $fechaHoraExpiracion = new \DateTime();
        $fechaHoraExpiracion->modify('+1 day');
        $datos = [
            'id' => $user->getId(),
            'fechaExpiracion' => $fechaHoraExpiracion->format("Y-m-d H:i:s"),
        ];
        $token = $this->encryptor->encrypt(json_encode($datos));
        return $this->router->generate('app_activar_usuario', [
            'token' => $token
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}