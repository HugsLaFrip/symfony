<?php

namespace App\Security\Voter;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerVoter extends Voter
{
    protected function supports(string $attribute, $subject)
    {
        if ($subject && !$subject instanceof Customer) {
            return false;
        }

        $policies = [
            'CAN_REMOVE',
            'CAN_EDIT',
            'CAN_LIST_CUSTOMERS',
            'CAN_CREATE_CUSTOMER',
            'CAN_LIST_ALL_CUSTOMERS'
        ];

        if (!in_array($attribute, $policies)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            return true;
        }

        if ($attribute === "CAN_LIST_ALL_CUSTOMERS") {
            return in_array('ROLE_MODERATOR', $user->getRoles());
        }

        if ($attribute === 'CAN_EDIT') {
            return $subject->user === $user;
        }

        if ($attribute === 'CAN_REMOVE' && (in_array('ROLE_MODERATOR', $user->getRoles()) || $subject->user === $user)) {
            return true;
        }

        if ($attribute === 'CAN_CREATE_CUSTOMER') {
            return !in_array('ROLE_MODERATOR', $user->getRoles());
        }

        return true;
    }
}
