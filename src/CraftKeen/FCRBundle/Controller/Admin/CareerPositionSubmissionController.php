<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\CareerPositionSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * CareerPositionSubmissionController controller.
 *
 * @Route("admin/careers/postion/submissions")
 */
class CareerPositionSubmissionController extends Controller
{
    /**
     * Lists all careerPositionSubmission entities.
     *
     * @Route("/", name="admin_careers_postion_submissions_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(CareerPositionSubmission::class)
                ->findAll(), 
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/*limit per page*/
        );

        return [
			'pagination' => $pagination,
		];
    }

    /**
     * Creates a new careerPositionSubmission entity.
     *
     * @Route("/new", name="admin_careers_postion_submissions_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function newAction(Request $request)
    {
        $careerPositionSubmission = new Careerpositionsubmission();
        $form = $this->createForm('CraftKeen\FCRBundle\Form\CareerPositionSubmissionType', $careerPositionSubmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            /** @var UploadedFile $file */
            $file = $careerPositionSubmission->getResume();
           
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('fcr_careers_resume_path'),
                $fileName
            );
            $careerPositionSubmission->setResume($fileName);
            $em->persist($careerPositionSubmission);
            $em->flush();

            return $this->redirectToRoute('admin_careers_postion_submissions_show', array('id' => $careerPositionSubmission->getId()));
        }

        return array(
            'careerPositionSubmission' => $careerPositionSubmission,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a careerPositionSubmission entity.
     *
     * @Route("/{id}", name="admin_careers_postion_submissions_show")
     * @Method("GET")
     * @Template()
     * @param CareerPositionSubmission $careerPositionSubmission
     * @return array
     */
    public function showAction(CareerPositionSubmission $careerPositionSubmission)
    {
        $deleteForm = $this->createDeleteForm($careerPositionSubmission);

        return array(
            'careerPositionSubmission' => $careerPositionSubmission,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing careerPositionSubmission entity.
     *
     * @Route("/{id}/edit", name="admin_careers_postion_submissions_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param CareerPositionSubmission $careerPositionSubmission
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, CareerPositionSubmission $careerPositionSubmission)
    {
        $deleteForm = $this->createDeleteForm($careerPositionSubmission);
        $editForm = $this->createForm('CraftKeen\FCRBundle\Form\CareerPositionSubmissionType', $careerPositionSubmission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_careers_postion_submissions_edit', array('id' => $careerPositionSubmission->getId()));
        }

        return [
            'careerPositionSubmission' => $careerPositionSubmission,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a careerPositionSubmission entity.
     *
     * @Route("/{id}", name="admin_careers_postion_submissions_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param CareerPositionSubmission $careerPositionSubmission
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, CareerPositionSubmission $careerPositionSubmission)
    {
        $form = $this->createDeleteForm($careerPositionSubmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($careerPositionSubmission);
            $em->flush();
        }

        return $this->redirectToRoute('admin_careers_postion_submissions_index');
    }

    /**
     * Creates a form to delete a careerPositionSubmission entity.
     *
     * @param CareerPositionSubmission $careerPositionSubmission The careerPositionSubmission entity
     *
     * @return Form The form
     */
    private function createDeleteForm(CareerPositionSubmission $careerPositionSubmission)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_careers_postion_submissions_delete', array('id' => $careerPositionSubmission->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
