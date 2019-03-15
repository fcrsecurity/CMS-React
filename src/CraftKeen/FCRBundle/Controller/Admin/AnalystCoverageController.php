<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\AnalystCoverage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * AnalystCoverageController
 *
 * @Route("admin/investors/analyst-coverage")
 */
class AnalystCoverageController extends BaseCrudController
{
    use InvestorsPermissionsTrait;

    /**
     * Lists all analystCoverage entities.
     *
     * @Route("/", name="admin_investors_analyst-coverage_index")
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
     * Creates a new analystCoverage entity.
     *
     * @Route("/new", name="admin_investors_analyst-coverage_new")
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
     * Finds and displays a analystCoverage entity.
     *
     * @Route("/{id}", name="admin_investors_analyst-coverage_show")
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
     * @Route("/{id}/revert/{version}", name="admin_investors_analyst-coverage_revert")
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
     * Displays a form to edit an existing analystCoverage entity.
     *
     * @Route("/{id}/edit", name="admin_investors_analyst-coverage_edit")
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
     * Displays a form to translate an existing analystCoverage entity.
     *
	 * @Route("/{id}/translate/{translateToLanguage}", name="admin_investors_analyst-coverage_translate_to")
	 *
     * @Method({"GET", "POST"})
	 * @Template()
     * @param Request $request
     * @param $id
     * @param Language $translateToLanguage
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function translateAction(Request $request, $id, Language $translateToLanguage, $formOptions = [])
    {
		return parent::translateAction($request, $id, $translateToLanguage, $formOptions);
    }

	/**
	 * Lists all analystCoverage entity translations.
	 *
	 * @Route("/{id}/translate", name="admin_investors_analyst-coverage_translate")
	 * @Method("GET")
	 * @Template()
     * @param Request $request
     * @param int $id
     * @return array
     */
	public function translateIndexAction(Request $request, $id)
    {
		return parent::translateIndexAction($request, $id);
	}

	/**
     * Deletes a analystCoverage entity.
     *
     * @Route("/{id}", name="admin_investors_analyst-coverage_delete")
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
     * Apply Workflow transition to analystCoverage.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_investors_analyst-coverage_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\AnalystCoverageRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(AnalystCoverage::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return AnalystCoverage
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new AnalystCoverage());
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\AnalystCoverageType';
    }
}
