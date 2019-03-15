<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\RetailArtCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * RetailArtCategoryController
 *
 * @Route("admin/community/retail-art/category")
 */
class RetailArtCategoryController extends BaseCrudController
{
    use HrPermissionsTrait;

    /**
     * Lists all retailArtCategory entities.
     *
     * @Route("/", name="admin_community_retail-art_category_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     * @return array
     */
    public function indexAction(Request $request, $filters = []) {
        return parent::indexAction($request, $filters = []);
    }

    /**
     * Creates a new retailArtCategory entity.
     *
     * @Route("/new", name="admin_community_retail-art_category_new")
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
     * Finds and displays a retailArtCategory entity.
     *
     * @Route("/{id}", name="admin_community_retail-art_category_show")
     * @Method("GET")
     * @Template()
     * @param int $id
     * @return array
     */
    public function showAction($id) {
        return parent::showAction($id);
    }

    /**
     * Displays a form to edit an existing retailArtCategory entity.
     *
     * @Route("/{id}/edit", name="admin_community_retail-art_category_edit")
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
     * Displays a form to translate an existing retailArtCategory entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="admin_community_retail-art_category_translate_to")
     *
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param $id
     * @param Language $translateToLanguage
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function translateAction(Request $request, $id, Language $translateToLanguage, $formOptions = []) {
        return parent::translateAction($request, $id, $translateToLanguage, $formOptions);
    }

    /**
     * Lists all retailArtCategory entity translations.
     *
     * @Route("/{id}/translate", name="admin_community_retail-art_category_translate")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function translateIndexAction(Request $request, $id) {
        return parent::translateIndexAction($request, $id);
    }

    /**
     * Deletes a retailArtCategory entity.
     *
     * @Route("/{id}", name="admin_community_retail-art_category_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        return parent::deleteAction($request, $id);
    }

    /**
     * Lists all retailArtCategory entity translations
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_community_retail-art_category_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\RetailArtCategoryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(RetailArtCategory::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return RetailArtCategory
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new RetailArtCategory() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\RetailArtCategoryType';
    }
}
