<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Repository\ActiveProvinceRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use CraftKeen\FCRBundle\Entity\ActiveProvince;

/**
 * ActiveProvinceController.
 *
 * @Route("admin/leasing/province")
 */
class ActiveProvinceController extends BaseCrudController
{
    use LeasingPermissionsTrait;

    /**
     * Lists all activeProvince entities.
     *
     * @Route("/", name="admin_fcr_leasing_province_index")
	 * @Security("has_role('ROLE_USER')")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     * @return array
     */
    public function indexAction(Request $request, $filters = [])
    {
       return parent::indexAction($request, $filters = []);
    }

    /**
     * Creates a new activeProvince entity.
     *
     * @Route("/new", name="admin_fcr_leasing_province_new")
	 * @Security("has_role('ROLE_CONTRIBUTOR') or has_role('ROLE_ADMINISTRATOR')")
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
     * Finds and displays a activeProvince entity.
     *
     * @Route("/{id}", name="admin_fcr_leasing_province_show")
	 * @Security("has_role('ROLE_ADMINISTRATOR')")
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
     * Finds and Rever a activeProvince entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_fcr_leasing_province_revert")
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
     * Displays a form to edit an existing activeProvince entity.
     *
     * @Route("/{id}/edit", name="admin_fcr_leasing_province_edit")
	 * @Security("has_role('ROLE_EDITOR') or has_role('ROLE_ADMINISTRATOR') ")
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
     * Displays a form to translate an existing activeProvince entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="admin_fcr_leasing_province_translate_to")
     *
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param $id
     * @param Language $translateToLanguage
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function translateAction(Request $request, $id, Language $translateToLanguage, $formOptions=[]) {
        return parent::translateAction($request, $id, $translateToLanguage, $formOptions);
    }

    /**
     * Lists all activeProvince entity translations.
     *
     * @Route("/{id}/translate", name="admin_fcr_leasing_province_translate")
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
     * Deletes a activeProvince entity.
     *
     * @Route("/{id}", name="admin_fcr_leasing_province_delete")
	 * @Security("has_role('ROLE_ADMINISTRATOR')")
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
     * Apply Workflow transition to activeProvince.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_fcr_leasing_province_apply_transition")
	 * @Security("has_role('ROLE_USER')")
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
     * @return ActiveProvinceRepository|ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(ActiveProvince::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return ActiveProvince
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new ActiveProvince());
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\ActiveProvinceType';
    }
}
