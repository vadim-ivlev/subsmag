<?php

namespace Rg\SubsmagBundle\Controller;

use Rg\SubsmagBundle\Entity\Periods;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Period controller.
 *
 */
class PeriodsController extends Controller
{
    /**
     * Lists all period entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $periods = $em->getRepository('RgSubsmagBundle:Periods')->findAll();

        return $this->render('periods/index.html.twig', array(
            'periods' => $periods,
        ));
    }

    /**
     * Creates a new period entity.
     *
     */
    public function newAction(Request $request)
    {
        $period = new Period();
        $form = $this->createForm('Rg\SubsmagBundle\Form\PeriodsType', $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($period);
            $em->flush();

            return $this->redirectToRoute('periods_show', array('id' => $period->getId()));
        }

        return $this->render('periods/new.html.twig', array(
            'period' => $period,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a period entity.
     *
     */
    public function showAction(Periods $period)
    {
        $deleteForm = $this->createDeleteForm($period);

        return $this->render('periods/show.html.twig', array(
            'period' => $period,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing period entity.
     *
     */
    public function editAction(Request $request, Periods $period)
    {
        $deleteForm = $this->createDeleteForm($period);
        $editForm = $this->createForm('Rg\SubsmagBundle\Form\PeriodsType', $period);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('periods_edit', array('id' => $period->getId()));
        }

        return $this->render('periods/edit.html.twig', array(
            'period' => $period,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a period entity.
     *
     */
    public function deleteAction(Request $request, Periods $period)
    {
        $form = $this->createDeleteForm($period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($period);
            $em->flush();
        }

        return $this->redirectToRoute('periods_index');
    }

    /**
     * Creates a form to delete a period entity.
     *
     * @param Periods $period The period entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Periods $period)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('periods_delete', array('id' => $period->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
