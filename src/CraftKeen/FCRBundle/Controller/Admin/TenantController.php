<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\Tenant;
use CraftKeen\FCRBundle\Repository\TenantRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * Tenant controller.
 *
 * @Route("admin/leasing/tenant")
 */
class TenantController extends BaseCrudController
{
    use LeasingPermissionsTrait;

    /**
     * Lists all tenant entities.
     *
     * @Route("/", name="admin_leasing_tenant_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     * @return array
     */
    public function indexAction(Request $request, $filters = []) {
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
     * Creates a new Tenant entity.
     *
     * @Route("/new", name="admin_leasing_tenant_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, $formOptions = []) {
        return parent::newAction($request, $formOptions);
    }

    /**
     * Finds and displays a Tenant entity.
     *
     * @Route("/{id}", name="admin_leasing_tenant_show")
     * @Method("GET")
     * @Template()
     * @param int $id
     * @return array
     */
    public function showAction($id) {
        return parent::showAction($id);
    }

    /**
     * Finds and Rever a Tenant entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_leasing_tenant_revert")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     * @param int $id
     * @param $version
     * @return RedirectResponse
     */
    public function revertAction($id, $version)
    {
        return parent::revertAction($id, $version);
    }

    /**
     * Displays a form to edit an existing Tenant entity.
     *
     * @Route("/{id}/edit", name="admin_leasing_tenant_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, $id, $formOptions = []) {
        return parent::editAction($request, $id, $formOptions);
    }

    /**
     * Deletes a Tenant entity.
     *
     * @Route("/{id}", name="admin_leasing_tenant_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        return parent::deleteAction($request, $id);
    }

    /**
     * Lists all Tenant entity translations
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_leasing_tenant_apply_transition")
     * @Method("GET")
     * @param Request $request
     * @param int $id
     * @return array|RedirectResponse
     */
    public function applyTransitionAction(Request $request, $id) {
        return parent::applyTransitionAction($request, $id);
    }

    /**
     * @see BaseApiController::getRepository()
     * @return TenantRepository|ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(Tenant::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return Tenant
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new Tenant() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\TenantType';
    }
}
