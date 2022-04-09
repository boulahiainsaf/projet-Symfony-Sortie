<?php

namespace App\Security;

use App\Entity\Participant;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ParticipantVoter extends Voter
{

    const UPDATE = 'update';
    const UPDATE_STATUS = 'update_status';
    const DELETE = 'delete';


    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::UPDATE, self::UPDATE_STATUS, self::DELETE])) {
            return false;
        }
        return true;
    }


    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof Participant) {
            // the user must be logged in; if not, deny access
            return false;
        }
        switch ($attribute) {
            case self::UPDATE:
                return $this->canUpdate($user);
            case self::UPDATE_STATUS:
                return $this->canUpdateEtat($user);
            case self::DELETE:
                return $this->canDelete($user);
        }

        throw new \LogicException('This code should not be reached!');

    }

    private function canUpdate(Participant $user)
    {
        return true;
    }
    private function canUpdateEtat(Participant $user)
    {
        if($user->getAdministrateur()) return true;
        return false;
    }
    private function canDelete(Participant $user)
    {
        if($user->getAdministrateur()) return true;
        return false;
    }
}