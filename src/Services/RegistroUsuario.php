<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistroUsuario
{

    private $router;


    public function __construct(UrlGeneratorInterface $router){
        $this->router = $router;
    }

    public function generarUrlActivacionUsuario(User $user){
        $fechaHoraExpiracion = new \DateTime();
        $fechaHoraExpiracion->modify('+1 day');

        $datos = [
            'id' => $user->getId(),
            'fechaExpiracion' => $fechaHoraExpiracion->format("Y-m-d H:i:s"),
        ];

        $token = json_encode($datos);

        return $this->router->generate('app_activar_usuario', [
            'token' => $token
        ]);

    }

}