<?php

namespace Rg\SubsmagBundle\Controller;

use Rg\SubsmagBundle\Entity\Subscribes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Subscribe controller.
 *
 */
class SubscribesController extends Controller
{
    /**
     * Lists all subscribe entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $subscribes = $em->getRepository('RgSubsmagBundle:Subscribes')->findAll();

        return $this->render('subscribes/index.html.twig', array(
            'subscribes' => $subscribes,
        ));
    }

    /**
     * Creates a new subscribe entity.
     *
     */
    public function newAction(Request $request)
    {
        $subscribe = new Subscribe();
        $form = $this->createForm('Rg\SubsmagBundle\Form\SubscribesType', $subscribe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subscribe);
            $em->flush();

            return $this->redirectToRoute('subscribes_show', array('id' => $subscribe->getId()));
        }

        return $this->render('subscribes/new.html.twig', array(
            'subscribe' => $subscribe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a subscribe entity.
     *
     */
    public function showAction(Subscribes $subscribe)
    {
        $deleteForm = $this->createDeleteForm($subscribe);

        return $this->render('subscribes/show.html.twig', array(
            'subscribe' => $subscribe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing subscribe entity.
     *
     */
    public function editAction(Request $request, Subscribes $subscribe)
    {
        $deleteForm = $this->createDeleteForm($subscribe);
        $editForm = $this->createForm('Rg\SubsmagBundle\Form\SubscribesType', $subscribe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('subscribes_edit', array('id' => $subscribe->getId()));
        }

        return $this->render('subscribes/edit.html.twig', array(
            'subscribe' => $subscribe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a subscribe entity.
     *
     */
    public function deleteAction(Request $request, Subscribes $subscribe)
    {
        $form = $this->createDeleteForm($subscribe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($subscribe);
            $em->flush();
        }

        return $this->redirectToRoute('subscribes_index');
    }

    /**
     * Creates a form to delete a subscribe entity.
     *
     * @param Subscribes $subscribe The subscribe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Subscribes $subscribe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('subscribes_delete', array('id' => $subscribe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
