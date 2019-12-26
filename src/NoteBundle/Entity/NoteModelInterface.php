<?php
/**
 * User: jszutkowski
 * Date: 2017-07-18
 * Time: 11:18
 */

namespace NoteBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

interface NoteModelInterface
{
    /**
     * @return ModelNoteInterface[]|ArrayCollection
     */
    public function getModelNotes();
    public function addModelNote(ModelNoteInterface $modelNote);
    public function removeModelNote(ModelNoteInterface $modelNote);
}