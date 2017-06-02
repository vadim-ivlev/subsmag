<?php

namespace Rg\SubsmagBundle\Controller;

use Rg\SubsmagBundle\Entity\Kits;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Kit controller.
 *
 */
class KitsController extends Controller
{
    /**
     * Lists all kit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $kits = $em->getRepository('RgSubsmagBundle:Kits')->findAll();

        return $this->render('kits/index.html.twig', array(
            'kits' => $kits,
        ));
    }

    /**
     * Creates a new kit entity.
     *
     */
    public function newAction(Request $request)
    {
        $kit = new Kit();
        $form = $this->createForm('Rg\SubsmagBundle\Form\KitsType', $kit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($kit);
            $em->flush();

            return $this->redirectToRoute('kits_show', array('id' => $kit->getId()));
        }

        return $this->render('kits/new.html.twig', array(
            'kit' => $kit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a kit entity.
     *
     */
    public function showAction(Kits $kit)
    {
        $deleteForm = $this->createDeleteForm($kit);

        return $this->render('kits/show.html.twig', array(
            'kit' => $kit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing kit entity.
     *
     */
    public function editAction(Request $request, Kits $kit)
    {
        $deleteForm = $this->createDeleteForm($kit);
        $editForm = $this->createForm('Rg\SubsmagBundle\Form\KitsType', $kit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('kits_edit', array('id' => $kit->getId()));
        }

        return $this->render('kits/edit.html.twig', array(
            'kit' => $kit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a kit entity.
     *
     */
    public function deleteAction(Request $request, Kits $kit)
    {
        $form = $this->createDeleteForm($kit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($kit);
            $em->flush();
        }

        return $this->redirectToRoute('kits_index');
    }

    /**
     * Creates a form to delete a kit entity.
     *
     * @param Kits $kit The kit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Kits $kit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kits_delete', array('id' => $kit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
