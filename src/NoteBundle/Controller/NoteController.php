<?php

namespace NoteBundle\Controller;

use NoteBundle\Entity\ModelNoteInterface;
use NoteBundle\Entity\NoteModelInterface;
use NoteBundle\Form\NoteType;
use NoteBundle\Security\NoteVoter;
use CoreBundle\Controller\CoreController;
use NoteBundle\Entity\Note;
use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Note controller.
 *
 * @Route("/admin/note/recruitment/note")
 */
class NoteController extends CoreController
{

    /**
     * Creates a new note entity.
     *
     * @Route("/{entity}/{entityId}/new", name="note_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,  string $entity, int $entityId)
    {
        $model = $this->findEntityByNameAndId($entity, $entityId);
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        if ($request->isMethod('post'))
        {
            $form->handleRequest($request);
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $modelNoteClass = $this->get('core.entity_mapper')->getModelNoteByType($entity);
                $modelNoteClass->setModel($model);
                $modelNoteClass->setNote($note);
                $note->setUser($this->getUser());
                $em->persist($note);
                $em->persist($modelNoteClass);
                $em->flush();
                return new JsonResponse("success");
            }
            else {
                $formErrors = $this->getFormErrors($form);
                return new JsonResponse($formErrors);
            }
        }

        return $this->render('NoteBundle:Note:form.html.twig', array(
            'note' => $note,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new note entity.
     *
     * @Route("/{entity}/{entityId}/list/", name="note_list")
     * @Method({"GET"})
     */
    public function listAction($entity, $entityId)
    {
        /** @var $model NoteModelInterface */
        $model = $this->findEntityByNameAndId($entity, $entityId);
        $results = [];
        foreach ($model->getModelNotes() as $modelNote)
        {
            if($modelNote && $modelNote->getNote()){
                $results[] = $modelNote->getNote();
            }
        }
        return $this->render('NoteBundle:Note:list.html.twig', array(
            'notes' => $results,
            'entityType' => $entity,
            'entityId' => $entityId
        ));
    }


    /**
     * Creates a new note entity.
     *
     * @Route("/{entity}/{entityId}/remove/{noteId}/", name="note_remove")
     * @Method({"POST"})
     */
    public function removeAction(Request $request, $entity, $entityId, $noteId)
    {
        $model = $this->findEntityByNameAndId($entity, $entityId);
        $entityNote = $this->findNoteFromEntityByNoteId($model, $noteId);

        $this->denyAccessUnlessGranted(NoteVoter::NOTE_REMOVE, $entityNote->getNote());

        $em = $this->getDoctrine()->getManager();

        $em->remove($entityNote->getNote());
        $em->remove($entityNote);
        $em->flush();

        return $this->redirectToBackUrlOrRoute($request, 'homepage');
    }


    /**
     * Creates a new note entity.
     *
     * @Route("/{entity}/{entityId}/edit/{noteId}/", name="note_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $entity, $entityId, $noteId)
    {
        $model = $this->findEntityByNameAndId($entity, $entityId);
        $entityNote = $this->findNoteFromEntityByNoteId($model, $noteId);
        $note = $entityNote->getNote();
        $this->denyAccessUnlessGranted(NoteVoter::NOTE_EDIT, $note);
        $form = $this->createForm(NoteType::class, $note);

        if ($request->isMethod('post'))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return new JsonResponse("success");
            }
            else
            {
                $formErrors = $this->getFormErrors($form);
                return new JsonResponse($formErrors);
            }
        }
        return $this->render('NoteBundle:Note:form.html.twig', array(
            'note' => $note,
            'form' => $form->createView()
        ));

    }

    protected function findEntityByNameAndId($entity, $entityId) : NoteModelInterface
    {
        $class = $this->get('core.entity_mapper')->getEntityClass($entity);

        if (!$class)
        {
            throw new AccessDeniedException('Entity not supported');
        }

        /** @var $entity NoteModelInterface */
        $entity = $this->getDoctrine()->getRepository($class)->find($entityId);

        if (!$entity)
        {
            throw new NotFoundHttpException('Entity not found');
        }
        return $entity;
    }


    protected function findNoteFromEntityByNoteId(NoteModelInterface $entity, $noteId) : ?ModelNoteInterface
    {
        $note = $this->getDoctrine()->getRepository(Note::class)->find($noteId);
        $criteria = Criteria::create()->where(Criteria::expr()->eq("note", $note));
        $modelNotes = $entity->getModelNotes()->matching($criteria);

        if(count($modelNotes) > 0)
        {
            $modelNote = $modelNotes[0];
        }
        else
        {
            $modelNote = null;
        }

        if(!$modelNote)
        {
            throw new NotFoundHttpException();
        }
        return $modelNote;
    }

}