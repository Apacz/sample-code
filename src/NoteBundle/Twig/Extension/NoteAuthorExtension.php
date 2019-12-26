<?php


namespace NoteBundle\Twig\Extension;

use NoteBundle\Entity\Note;

class NoteAuthorExtension extends \Twig_Extension {

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {

        return array(
            'noteAuthor' => new \Twig_SimpleFunction(
                'noteAuthor',
                function(Note $note){
                    $user = $note->getUser();
                    return $user->getFirstName() . ' ' . $user->getLastName();
                }
            ),
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'author';
    }
}
