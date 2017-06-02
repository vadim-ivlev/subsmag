<?php

namespace Rg\SubsmagBundle\Controller;

use Rg\SubsmagBundle\Entity\Zones;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Zone controller.
 *
 */
class ZonesController extends Controller
{
    /**
     * Lists all zone entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $zones = $em->getRepository('RgSubsmagBundle:Zones')->findAll();

        return $this->render('zones/index.html.twig', array(
            'zones' => $zones,
        ));
    }

    /**
     * Creates a new zone entity.
     *
     */
    public function newAction(Request $request)
    {
        $zone = new Zone();
        $form = $this->createForm('Rg\SubsmagBundle\Form\ZonesType', $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($zone);
            $em->flush();

            return $this->redirectToRoute('zones_show', array('id' => $zone->getId()));
        }

        return $this->render('zones/new.html.twig', array(
            'zone' => $zone,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a zone entity.
     *
     */
    public function showAction(Zones $zone)
    {
        $deleteForm = $this->createDeleteForm($zone);

        return $this->render('zones/show.html.twig', array(
            'zone' => $zone,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing zone entity.
     *
     */
    public function editAction(Request $request, Zones $zone)
    {
        $deleteForm = $this->createDeleteForm($zone);
        $editForm = $this->createForm('Rg\SubsmagBundle\Form\ZonesType', $zone);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('zones_edit', array('id' => $zone->getId()));
        }

        return $this->render('zones/edit.html.twig', array(
            'zone' => $zone,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a zone entity.
     *
     */
    public function deleteAction(Request $request, Zones $zone)
    {
        $form = $this->createDeleteForm($zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($zone);
            $em->flush();
        }

        return $this->redirectToRoute('zones_index');
    }

    /**
     * Creates a form to delete a zone entity.
     *
     * @param Zones $zone The zone entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Zones $zone)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('zones_delete', array('id' => $zone->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
