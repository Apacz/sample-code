<?php
/**
 * User: jszutkowski
 * Date: 2017-07-18
 * Time: 11:17
 */

namespace NoteBundle\Entity;


interface ModelNoteInterface
{
    public function getModel();
    public function setModel(NoteModelInterface $noteModel);
    public function getNote(): Note;
    public function setNote(Note $note);
}