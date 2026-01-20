<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User;

class RoleVoter extends Voter
{
    // роли, которые будем проверять через @IsGranted
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['ROLE_ADMIN', 'ROLE_CLIENT']);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // проверяем роль
        return $user->getRole() === str_replace('ROLE_', '', $attribute);
    }
}
