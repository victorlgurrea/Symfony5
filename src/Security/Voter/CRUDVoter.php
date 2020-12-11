<?php

namespace App\Security\Voter;

use App\Entity\Categoria;
use App\Entity\Etiqueta;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CRUDVoter extends Voter
{
    const VER = "ver";
    const EDITAR = "editar";
    const ELIMINAR = "eliminar";
    const ENTIDADES_AFECTADAS = [
        Categoria::class,
        Etiqueta::class,
        //Marcador::class
    ];

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $subject)
    {
        if(! in_array($attribute, [self::VER, self::EDITAR, self::ELIMINAR])) {
            return false;
        }
        if(! in_array(get_class($subject), self::ENTIDADES_AFECTADAS)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::VER:
                return $this->canView($subject, $user);
                break;
            case self::EDITAR:
                return $this->canEdit($subject, $user);
                break;
            case self::ELIMINAR:
                return $this->canDelete($subject, $user);
                break;
        }

        return false;
    }

    private function canDelete($subject, User $usuario) {

        if ($this->security->isGranted('ROLE_SUPER_ADMIN') || $usuario === $subject->getUsuario()) {
            return true;
        }
        return  false;
    }

    private function canView($subject, User $usuario) {
        return $usuario === $subject->getUsuario();
    }

    private function canEdit($subject, User $usuario) {
        if ($this->security->isGranted('ROLE_SUPER_ADMIN') || $usuario === $subject->getUsuario()) {
            return true;
        }
        return  false;
    }
}