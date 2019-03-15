<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\Debenture;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Debenture controller.
 *
 * @Route("admin/investors/debenture")
 */
class DebentureController extends BaseCrudController
{
    use InvestorsPermissionsTrait;

    /**
     * Lists all debenture entities.
     *
     * @Route("/", name="admin_investors_debenture_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     * @return array
     */
    public function indexAction(Request $request, $filters = [])
    {
       $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getRepository()
                ->findAll(),
            $request->query->getInt('page', 1), /* page number */
            ($request->query->get('per_page')) ? (int) $request->query->get('per_page') : 10/* limit per page */
        );

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * Creates a new Debenture entity.
     *
     * @Route("/new", name="admin_investors_debenture_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, $formOptions = [])
    {
        return parent::newAction($request, $formOptions);
    }

    /**
     * Finds and displays a Debenture entity.
     *
     * @Route("/{id}", name="admin_investors_debenture_show")
     * @Method("GET")
     * @Template()
     * @param int $id
     * @return array
     */
    public function showAction($id)
    {
        return parent::showAction($id);
    }

    /**
     * Finds and Revert a Debenture entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_investors_debenture_revert")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     * @param int $id
     * @param $version
     * @return RedirectResponse
     */
    public function revertAction($id, $version) {
        return parent::revertAction($id, $version);
    }

    /**
     * Displays a form to edit an existing Debenture entity.
     *
     * @Route("/{id}/edit", name="admin_investors_debenture_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, $id, $formOptions = [])
    {
        return parent::editAction($request, $id, $formOptions);
    }

	/**
     * Deletes a Debenture entity.
     *
     * @Route("/{id}", name="admin_investors_debenture_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Apply Workflow transition to Debenture.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_investors_debenture_apply_transition")
     * @Method("GET")
     * @param Request $request
     * @param int $id
     * @return array|RedirectResponse
     */
    public function applyTransitionAction(Request $request, $id)
    {
        return parent::applyTransitionAction($request, $id);
    }

    /**
     * @see BaseApiController::getRepository()
     * @return \CraftKeen\FCRBundle\Repository\DebentureRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(Debenture::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return Debenture
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new Debenture() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\DebentureType';
    }
}
