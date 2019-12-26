<?php

namespace NoteBundle\Security;


use NoteBundle\Entity\Note;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class NoteVoter extends Voter
{
    const NOTE_EDIT = 'NOTE_EDIT';
    const NOTE_REMOVE = 'NOTE_REMOVE';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [static::NOTE_EDIT, static::NOTE_REMOVE]) && $subject instanceof Note;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if($user->getId() == $subject->getUser()->getId()){
            return true;
        }

        return false;
    }
}
