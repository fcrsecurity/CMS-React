<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\ConferenceCall;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Conferencecall controller.
 *
 * @Route("admin/investors/conference-call")
 */
class ConferenceCallController extends BaseCrudController
{
    use InvestorsPermissionsTrait;

    /**
     * Lists all conferenceCall entities.
     *
     * @Route("/", name="admin_investors_conference-call_index")
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
	 * Creates a new ConferenceCall entity.
	 *
	 * @Route("/new", name="admin_investors_conference-call_new")
	 * @Method({"GET", "POST"})
	 * @Template()
     * @param Request $request
     * @param array $formOptions
     * @return array|RedirectResponse
     */
	public function newAction(Request $request, $formOptions = []) {
		$formOptions = ['language' => $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()];
		return parent::newAction($request, $formOptions);
	}

	/**
	 * Finds and displays a ConferenceCall entity.
	 *
	 * @Route("/{id}", name="admin_investors_conference-call_show")
	 * @Method("GET")
	 * @Template()
     * @param int $id
     * @return array
     */
	public function showAction($id) {
		return parent::showAction($id);
	}

    /**
     * Finds and Rever a ConferenceCall entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_investors_conference-call_revert")
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
	 * Displays a form to edit an existing ConferenceCall entity.
	 *
	 * @Route("/{id}/edit", name="admin_investors_conference-call_edit")
	 * @Method({"GET", "POST"})
	 * @Template()
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     * @return array|RedirectResponse
     */
	public function editAction(Request $request, $id, $formOptions = []) {
		$formOptions = ['language' => $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()];
		return parent::editAction($request, $id, $formOptions);
	}

	/**
	 * Displays a form to translate an existing ConferenceCall entity.
	 *
	 * @Route("/{id}/translate/{translateToLanguage}", name="admin_investors_conference-call_translate_to")
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
	 * Lists all ConferenceCall entity translations.
	 *
	 * @Route("/{id}/translate", name="admin_investors_conference-call_translate")
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
	 * Deletes a ConferenceCall entity.
	 *
	 * @Route("/{id}", name="admin_investors_conference-call_delete")
	 * @Method("DELETE")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
	public function deleteAction(Request $request, $id) {
		return parent::deleteAction($request, $id);
	}

	/**
	 * Lists all ConferenceCall entity translations
	 *
	 * @Route("/apply-transition/{id}/{transition}", name="admin_investors_conference-call_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\ConferenceCallRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
	public function getRepository() {
		return $this->getDoctrine()->getManager()->getRepository(ConferenceCall::class);
	}

	/**
	 * @see BaseApiController::getNewEntity()
     * @return ConferenceCall
     */
	public function getNewEntity() {
		return $this->setEntityDefaults( new ConferenceCall() );
	}

	/**
	 * @see BaseApiController::getEntityFormType()
	 * @return String
	 */
	public function getEntityFormType() {
		return 'CraftKeen\FCRBundle\Form\ConferenceCallType';
	}
}
