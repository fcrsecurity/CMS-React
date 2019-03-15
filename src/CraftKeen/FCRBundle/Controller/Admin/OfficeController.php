<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\Office;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * Office controller.
 *
 * @Route("admin/about/office")
 */
class OfficeController extends BaseCrudController
{
    use HrPermissionsTrait;

    /**
     * Lists all office entities.
     *
     * @Route("/", name="admin_about_office_index")
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
     * Creates a new Office entity.
     *
     * @Route("/new", name="admin_about_office_new")
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
     * Finds and displays a Office entity.
     *
     * @Route("/{id}", name="admin_about_office_show")
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
     * @Route("/{id}/revert/{version}", name="admin_about_office_revert")
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
     * Displays a form to edit an existing Office entity.
     *
     * @Route("/{id}/edit", name="admin_about_office_edit")
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
     * Displays a form to translate an existing Office entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="admin_about_office_translate_to")
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
     * Lists all Office entity translations.
     *
     * @Route("/{id}/translate", name="admin_about_office_translate")
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
     * Deletes a Office entity.
     *
     * @Route("/{id}", name="admin_about_office_delete")
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
     * Apply Workflow transition to Office.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_about_office_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\OfficeRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(Office::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return Office
     */
    public function getNewEntity() {
        return $this->setEntityDefaults(new Office());
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\OfficeType';
    }
}
