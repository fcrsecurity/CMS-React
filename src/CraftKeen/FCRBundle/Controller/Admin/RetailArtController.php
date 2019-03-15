<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\RetailArt;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * RetailArtController
 *
 * @Route("admin/community/retail-art")
 */
class RetailArtController extends BaseCrudController
{
    use HrPermissionsTrait;

    /**
     * Lists all retailArt entities.
     *
     * @Route("/", name="admin_community_retail-art_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     *
     * @return array
     */
    public function indexAction(Request $request, $filters = [])
    {
        return parent::indexAction($request, $filters = []);
    }

    /**
     * Creates a new Retailart entity.
     *
     * @Route("/new", name="admin_community_retail-art_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, $formOptions = [])
    {
        $formOptions = ['language' => $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()];
        return parent::newAction($request, $formOptions);
    }

    /**
     * Finds and displays a Retailart entity.
     *
     * @Route("/{id}", name="admin_community_retail-art_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Template()
     * @param int $id
     *
     * @return array
     */
    public function showAction($id)
    {
        return parent::showAction($id);
    }

    /**
     * Finds and Rever a activeProvince entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_community_retail-art_revert")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     * @param int $id
     * @param $version
     *
     * @return RedirectResponse
     */
    public function revertAction($id, $version)
    {
        return parent::revertAction($id, $version);
    }

    /**
     * Displays a form to edit an existing Retailart entity.
     *
     * @Route("/{id}/edit", name="admin_community_retail-art_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, $id, $formOptions = [])
    {
        $formOptions = ['language' => $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()];
        return parent::editAction($request, $id, $formOptions);
    }

    /**
     * Displays a form to translate an existing Retailart entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="admin_community_retail-art_translate_to")
     *
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param $id
     * @param Language $translateToLanguage
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     */
    public function translateAction(Request $request, $id, Language $translateToLanguage, $formOptions = [])
    {
        $formOptions = ['language' => $translateToLanguage];
        return parent::translateAction($request, $id, $translateToLanguage, $formOptions);
    }

    /**
     * Lists all Retailart entity translations.
     *
     * @Route("/{id}/translate", name="admin_community_retail-art_translate")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param int $id
     *
     * @return array
     */
    public function translateIndexAction(Request $request, $id)
    {
        return parent::translateIndexAction($request, $id);
    }

    /**
     * Deletes a Retailart entity.
     *
     * @Route("/{id}", name="admin_community_retail-art_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Lists all Retailart entity translations
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_community_retail-art_apply_transition")
     * @Method("GET")
     * @param Request $request
     * @param int $id
     *
     * @return array|RedirectResponse
     */
    public function applyTransitionAction(Request $request, $id)
    {
        return parent::applyTransitionAction($request, $id);
    }

    /**
     * @see BaseApiController::getRepository()
     * @return \CraftKeen\FCRBundle\Repository\RetailArtRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(RetailArt::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return RetailArt
     */
    public function getNewEntity()
    {
        return $this->setEntityDefaults(new RetailArt());
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType()
    {
        return 'CraftKeen\FCRBundle\Form\RetailArtType';
    }
}
