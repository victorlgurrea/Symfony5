<?php

namespace App\Security\Voter;

use App\Entity\Marcador;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MarcadorVoter extends Voter
{
    const VER = "ver";
    const EDITAR = "editar";
    const ELIMINAR = "eliminar";

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

        if(! $subject instanceof Marcador) {
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

    private function canDelete(Marcador $marcador, User $usuario) {

        if ($this->security->isGranted('ROLE_SUPER_ADMIN') || $usuario === $marcador->getUsuario()) {
            return true;
        }
        return  false;
    }

    private function canView(Marcador $marcador, User $usuario) {
        return $usuario === $marcador->getUsuario();
    }

    private function canEdit(Marcador $marcador, User $usuario) {
        if ($this->security->isGranted('ROLE_SUPER_ADMIN') || $usuario === $marcador->getUsuario()) {
            return true;
        }
        return  false;
    }
}
