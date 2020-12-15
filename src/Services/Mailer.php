<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{

    private $emailAplication;
    private $emailManager;
    private $registroUsuario;

    public function __construct(string $emailAplication, RegistroUsuario $registroUsuario,MailerInterface $emailManager){
        $this->emailAplication = $emailAplication;
        $this->emailManager = $emailManager;
        $this->registroUsuario = $registroUsuario;
    }

    public function email_registro_usuario(User $user)
    {
        $this->enviarEmail($user->getEmail(), "Bienvenido a subastas!!", "email/registro.html.twig", [
            'user' => $user,
            'url_activa_usuario' => $this->registroUsuario->generarUrlActivacionUsuario($user),
        ]);
    }

    public function enviarEmail(string $para, string $titulo, string $template, array $params)
    {
        $email = (new TemplatedEmail())
        ->from($this->emailAplication)
        ->to($para)
        ->subject($titulo)
        ->htmlTemplate($template)
        ->context($params)
        ;

        $this->emailManager->send($email);
    }
}