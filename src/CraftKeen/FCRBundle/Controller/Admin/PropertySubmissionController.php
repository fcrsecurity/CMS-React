<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\PropertySubmission;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * PropertySubmissionController
 *
 * @Route("admin/leasing/property/submission")
 */
class PropertySubmissionController extends Controller
{
    /**
     * Lists all propertySubmission entities.
     *
     * @Route("/", name="craftkeen_fcr_admin_leasing_property_submission_index")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws LogicException
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertySubmissionCsvType', null,
            array(
                'action' => $this->generateUrl('craftkeen_fcr_admin_leasing_property_submission_index'),
                'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository(PropertySubmission::class)->findAll();

            $response = new StreamedResponse();
            $response->setCallback(
                function () use ($data) {
                    $handle = fopen('php://output', 'r+');

                    $buf = [
                        'Name',
                        'Phone',
                        'email',
                        'Property Code',
                        'Inquiry Type',
                        'Square Footage',
                        'User comment'
                    ];
                    fputcsv($handle, $buf);

                    /** @var PropertySubmission $row */
                    foreach ($data as $row) {
                        $buf = array(
                            $row->getName(),
                            $row->getPhone(),
                            $row->getEmail(),
                            $row->getProperty()->getCode(),
                            $row->getInquryType(),
                            $row->getSquareFootage(),
                            $row->getComment()
                        );
                        fputcsv($handle, $buf);
                    }
                    fclose($handle);
                }
            );
            $filename = 'property_submissions_export_'.date('Y-m-d_H-i-s',time()).'.csv';
            $response->headers->set('Content-Type', 'application/force-download');
            $response->headers->set('Content-Disposition','attachment; filename="'.$filename.'"');

            return $response;
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(PropertySubmission::class)
                ->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/*limit per page*/
        );

        return $this->render('CraftKeenFCRBundle:Admin:PropertySubmission/index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new propertySubmission entity.
     *
     * @Route("/new", name="craftkeen_fcr_admin_leasing_property_submission_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $propertySubmission = new Propertysubmission();
        $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertySubmissionType', $propertySubmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertySubmission->setStatus('draft');
            $em = $this->getDoctrine()->getManager();
            $em->persist($propertySubmission);
            $em->flush();

            return $this->redirectToRoute('admin_leasing_property_submission_show', array('id' => $propertySubmission->getId()));
        }

        return $this->render('CraftKeenFCRBundle:Admin:PropertySubmission/new.html.twig', array(
            'propertySubmission' => $propertySubmission,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a propertySubmission entity.
     *
     * @Route("/{id}", name="admin_leasing_property_submission_show")
     * @Method("GET")
     * @param PropertySubmission $propertySubmission
     * @return Response
     */
    public function showAction(PropertySubmission $propertySubmission)
    {
        $deleteForm = $this->createDeleteForm($propertySubmission);

        return $this->render('CraftKeenFCRBundle:Admin:PropertySubmission/show.html.twig', array(
            'propertySubmission' => $propertySubmission,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing propertySubmission entity.
     *
     * @Route("/{id}/edit", name="craftkeen_fcr_admin_leasing_property_submission_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param PropertySubmission $propertySubmission
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, PropertySubmission $propertySubmission)
    {
        $deleteForm = $this->createDeleteForm($propertySubmission);
        $editForm = $this->createForm('CraftKeen\FCRBundle\Form\PropertySubmissionType', $propertySubmission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($propertySubmission->getStatus() == 'live'){
                $propertySubmission->setStatus('draft');
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('craftkeen_fcr_admin_leasing_property_submission_edit', array('id' => $propertySubmission->getId()));
        }

        return $this->render('CraftKeenFCRBundle:Admin:PropertySubmission/edit.html.twig', array(
            'propertySubmission' => $propertySubmission,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a propertySubmission entity.
     *
     * @Route("/{id}", name="admin_leasing_property_submission_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param PropertySubmission $propertySubmission
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, PropertySubmission $propertySubmission)
    {
        $form = $this->createDeleteForm($propertySubmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($propertySubmission);
            $em->flush();
        }

        return $this->redirectToRoute('craftkeen_fcr_admin_leasing_property_submission_index');
    }

    /**
     * Creates a form to delete a propertySubmission entity.
     *
     * @param PropertySubmission $propertySubmission The propertySubmission entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PropertySubmission $propertySubmission)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_leasing_property_submission_delete', array('id' => $propertySubmission->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Lists all activeProvince entities.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_leasing_property_submission_apply_transition")
     * @Method("GET")
     * @param Request $request
     * @param PropertySubmission $object
     * @return RedirectResponse
     */
    public function applyTransitionAction(Request $request, PropertySubmission $object)
    {
        $transition = $request->get('transition');
        try {
            $this->get('workflow.temporary_publishing')->apply($object, $transition);
            $this->getDoctrine()->getManager()->flush();
        }
        catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('admin_leasing_property_submission_show', ['id' => $object->getId()])
        );
    }
}
