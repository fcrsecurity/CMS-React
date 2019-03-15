<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\People;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Person controller.
 *
 * @Route("admin/careers/people")
 */
class PeopleController extends BaseCrudController
{
    use HrPermissionsTrait;

    /**
     * Lists all person entities.
     *
     * @Route("/", name="admin_careers_people_index")
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
     * Creates a new People entity.
     *
     * @Route("/new", name="admin_careers_people_new")
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
     * Finds and displays a People entity.
     *
     * @Route("/{id}", name="admin_careers_people_show")
     * @Method("GET")
     * @Template()
     * @param int $id
     * @return array
     */
    public function showAction($id) {
        return parent::showAction($id);
    }

    /**
     * Finds and Rever a People entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_careers_people_revert")
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
     * Displays a form to edit an existing People entity.
     *
     * @Route("/{id}/edit", name="admin_careers_people_edit")
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
     * Displays a form to translate an existing People entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="admin_careers_people_translate_to")
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
     * Lists all People entity translations.
     *
     * @Route("/{id}/translate", name="admin_careers_people_translate")
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
     * Deletes a People entity.
     *
     * @Route("/{id}", name="admin_careers_people_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        return parent::deleteAction($request, $id);
    }

    /**
     * Lists all People entity translations
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_careers_people_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\PeopleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(People::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return People
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new People() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\PeopleType';
    }
}
