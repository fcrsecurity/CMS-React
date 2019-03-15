<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\Manager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * Manager controller.
 *
 * @Route("admin/leasing/manager")
 */
class ManagerController extends BaseCrudController
{
    use LeasingPermissionsTrait;

    /**
     * Lists all manager entities.
     *
     * @Route("/", name="admin_leasing_manager_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, $filters = [])
    {
       return parent::indexAction($request, $filters = []);
    }

    /**
     * Creates a new Manager entity.
     *
     * @Route("/new", name="admin_leasing_manager_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request, $formOptions = [])
    {
        return parent::newAction($request, $formOptions);
    }

    /**
     * Finds and displays a Manager entity.
     *
     * @Route("/{id}", name="admin_leasing_manager_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        return parent::showAction($id);
    }

    /**
     * Finds and Rever a activeProvince entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_leasing_manager_revert")
	 * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     * @Template()
     */
    public function revertAction($id, $version)
    {
        return parent::revertAction($id, $version);
    }

    /**
     * Displays a form to edit an existing Manager entity.
     *
     * @Route("/{id}/edit", name="admin_leasing_manager_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, $id, $formOptions = [])
    {
        return parent::editAction($request, $id, $formOptions);
    }

	/**
     * Displays a form to translate an existing Manager entity.
     *
	 * @Route("/{id}/translate/{translateToLanguage}", name="admin_leasing_manager_translate_to")
	 *
     * @Method({"GET", "POST"})
	 * @Template()
     */
    public function translateAction(Request $request, $id, Language $translateToLanguage, $formOptions = [])
    {
		return parent::translateAction($request, $id, $translateToLanguage, $formOptions);
    }

	/**
	 * Lists all Manager entity translations.
	 *
	 * @Route("/{id}/translate", name="admin_leasing_manager_translate")
	 * @Method("GET")
	 * @Template()
	 */
	public function translateIndexAction(Request $request, $id)
    {
		return parent::translateIndexAction($request, $id);
	}

	/**
     * Deletes a Manager entity.
     *
     * @Route("/{id}", name="admin_leasing_manager_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Apply Workflow transition to Manager.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_leasing_manager_apply_transition")
     * @Method("GET")
     */
    public function applyTransitionAction(Request $request, $id)
    {
        return parent::applyTransitionAction($request, $id);
    }

    /**
     * @see BaseApiController::getRepository()
     * @return EntityRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(Manager::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return Manager
     */
    public function getNewEntity() {
        return $this->setEntityDefaults(new Manager());
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\ManagerType';
    }
}
