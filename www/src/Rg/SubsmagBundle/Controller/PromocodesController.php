<?php

namespace Rg\SubsmagBundle\Controller;

use Rg\SubsmagBundle\Entity\Promocodes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Promocode controller.
 *
 */
class PromocodesController extends Controller
{
    /**
     * Lists all promocode entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $promocodes = $em->getRepository('RgSubsmagBundle:Promocodes')->findAll();

        return $this->render('promocodes/index.html.twig', array(
            'promocodes' => $promocodes,
        ));
    }

    /**
     * Creates a new promocode entity.
     *
     */
    public function newAction(Request $request)
    {
        $promocode = new Promocode();
        $form = $this->createForm('Rg\SubsmagBundle\Form\PromocodesType', $promocode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promocode);
            $em->flush();

            return $this->redirectToRoute('promocodes_show', array('id' => $promocode->getId()));
        }

        return $this->render('promocodes/new.html.twig', array(
            'promocode' => $promocode,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a promocode entity.
     *
     */
    public function showAction(Promocodes $promocode)
    {
        $deleteForm = $this->createDeleteForm($promocode);

        return $this->render('promocodes/show.html.twig', array(
            'promocode' => $promocode,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing promocode entity.
     *
     */
    public function editAction(Request $request, Promocodes $promocode)
    {
        $deleteForm = $this->createDeleteForm($promocode);
        $editForm = $this->createForm('Rg\SubsmagBundle\Form\PromocodesType', $promocode);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('promocodes_edit', array('id' => $promocode->getId()));
        }

        return $this->render('promocodes/edit.html.twig', array(
            'promocode' => $promocode,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a promocode entity.
     *
     */
    public function deleteAction(Request $request, Promocodes $promocode)
    {
        $form = $this->createDeleteForm($promocode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($promocode);
            $em->flush();
        }

        return $this->redirectToRoute('promocodes_index');
    }

    /**
     * Creates a form to delete a promocode entity.
     *
     * @param Promocodes $promocode The promocode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Promocodes $promocode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('promocodes_delete', array('id' => $promocode->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
