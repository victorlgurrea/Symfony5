<?php

namespace App\Validator;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlAccesibleValidator extends ConstraintValidator
{
    private $clienteHttp;

    public function __construct(HttpClientInterface $clienteHttp)
    {
        $this->clienteHttp = $clienteHttp;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\UrlAccesible */

        if (null === $value || '' === $value) {
            return;
        }

        $codigoEstado = $this->clienteHttp->obtenerCodigoUrl($value);

        if ($codigoEstado === null) {
            $codigoEstado = 'ERROR';
        }
       
        if($codigoEstado !== 200) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ codigo }}', $codigoEstado)
            ->addViolation();
        }
        
    }
}
