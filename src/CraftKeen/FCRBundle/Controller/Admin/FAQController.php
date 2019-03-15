<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\FAQ;
use CraftKeen\FCRBundle\Repository\FAQRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * FAQ controller.
 *
 * @Route("admin/faq")
 */
class FAQController extends BaseCrudController
{
    /**
     * Lists all FAQ entities.
     *
     * @Route("/", name="admin_faq_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     * @return array
     */
    public function indexAction(Request $request, $filters = [])
    {
        $categoryFilter['category'] = [
            ['id' => 1, 'category' => 'Careers'],
            ['id' => 2, 'category' => 'Investors'],
        ];
        return parent::indexAction($request, $categoryFilter);
    }

    /**
     * Creates a new FAQ entity.
     *
     * @Route("/new", name="admin_faq_new")
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
     * Finds and displays a FAQ entity.
     *
     * @Route("/{id}", name="admin_faq_show")
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
     * @Route("/{id}/revert/{version}", name="admin_faq_revert")
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
     * Displays a form to edit an existing FAQ entity.
     *
     * @Route("/{id}/edit", name="admin_faq_edit")
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
     * Displays a form to translate an existing FAQ entity.
     *
	 * @Route("/{id}/translate/{translateToLanguage}", name="admin_faq_translate_to")
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
	 * Lists all FAQ entity translations.
	 *
	 * @Route("/{id}/translate", name="admin_faq_translate")
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
     * Deletes a FAQ entity.
     *
     * @Route("/{id}", name="admin_faq_delete")
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
     * Apply Workflow transition to FAQ.
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_faq_apply_transition")
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
     * @return FAQRepository|ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(FAQ::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return FAQ
     */
    public function getNewEntity() {
        return new FAQ();
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\FAQType';
    }
}
