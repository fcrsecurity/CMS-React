<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\RetailArtGallery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * Retailartgallery controller.
 *
 * @Route("admin/community/retail-art/gallery")
 */
class RetailArtGalleryController extends BaseCrudController
{
    use HrPermissionsTrait;

    /**
     * Lists all retailArtGallery entities.
     *
     * @Route("/", name="admin_community_retail-art_gallery_index")
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
     * Creates a new retailArtGallery entity.
     *
     * @Route("/new", name="admin_community_retail-art_gallery_new")
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
     * Finds and displays a retailArtGallery entity.
     *
     * @Route("/{id}", name="admin_community_retail-art_gallery_show")
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
     * Displays a form to edit an existing retailArtGallery entity.
     *
     * @Route("/{id}/edit", name="admin_community_retail-art_gallery_edit")
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
     * Deletes a retailArtGallery entity.
     *
     * @Route("/{id}", name="admin_community_retail-art_gallery_delete")
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
     * Apply Workflow transition to retailArtGallery.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_community_retail-art_gallery_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\PropertyGalleryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(RetailArtGallery::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return RetailArtGallery
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new RetailArtGallery() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\RetailArtGalleryType';
    }
}
