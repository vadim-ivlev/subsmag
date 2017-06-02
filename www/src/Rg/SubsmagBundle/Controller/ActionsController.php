<?php

namespace Rg\SubsmagBundle\Controller;

use Rg\SubsmagBundle\Entity\Actions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Action controller.
 *
 */
class ActionsController extends Controller
{
    /**
     * Lists all action entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $actions = $em->getRepository('RgSubsmagBundle:Actions')->findAll();

        return $this->render('actions/index.html.twig', array(
            'actions' => $actions,
        ));
    }

    /**
     * Creates a new action entity.
     *
     */
    public function newAction(Request $request)
    {
        $action = new Action();
        $form = $this->createForm('Rg\SubsmagBundle\Form\ActionsType', $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();

            return $this->redirectToRoute('actions_show', array('id' => $action->getId()));
        }

        return $this->render('actions/new.html.twig', array(
            'action' => $action,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a action entity.
     *
     */
    public function showAction(Actions $action)
    {
        $deleteForm = $this->createDeleteForm($action);

        return $this->render('actions/show.html.twig', array(
            'action' => $action,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing action entity.
     *
     */
    public function editAction(Request $request, Actions $action)
    {
        $deleteForm = $this->createDeleteForm($action);
        $editForm = $this->createForm('Rg\SubsmagBundle\Form\ActionsType', $action);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actions_edit', array('id' => $action->getId()));
        }

        return $this->render('actions/edit.html.twig', array(
            'action' => $action,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a action entity.
     *
     */
    public function deleteAction(Request $request, Actions $action)
    {
        $form = $this->createDeleteForm($action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($action);
            $em->flush();
        }

        return $this->redirectToRoute('actions_index');
    }

    /**
     * Creates a form to delete a action entity.
     *
     * @param Actions $action The action entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Actions $action)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actions_delete', array('id' => $action->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
