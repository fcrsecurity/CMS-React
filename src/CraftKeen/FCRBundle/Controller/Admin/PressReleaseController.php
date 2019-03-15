<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\CMS\PageBundle\Repository\RouteRepository;
use CraftKeen\FCRBundle\Repository\PressReleaseRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\FCRBundle\Entity\PressRelease;
use CraftKeen\CMS\PageBundle\Entity\Route as PageRoute;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use CraftKeen\CMS\AdminBundle\Entity\Logs;

/**
 * PressReleaseController
 *
 * @Route("admin/leasing/press-release")
 */
class PressReleaseController extends BaseCrudController
{
    use InvestorsPermissionsTrait;

    /**
     * Lists all pressRelease entities.
     *
     * @Route("/", name="admin_leasing_press-release_index")
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
     * Creates a new PressRelease entity.
     *
     * @Route("/new", name="admin_leasing_press-release_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     * @throws \LogicException
     */
    public function newAction(Request $request, $formOptions = [])
    {
        $object = new PressRelease();
        $form = $this->createForm($this->getEntityFormType(), $object, $formOptions);
        $form->handleRequest($request);
        if ( $object->getContent() === '' ) {
            $this->addFlash('warning', 'Content not set');
            return [
                'object' => $object,
                'form' => $form->createView(),
            ];
        }
        return parent::newAction($request, $formOptions);
    }

    /**
     * Finds and displays a PressRelease entity.
     *
     * @Route("/{id}", name="admin_leasing_press-release_show")
     * @Method("GET")
     * @Template()
     * @param int $id
     *
     * @return array
     */
    public function showAction($id)
    {
        $object = $this->findRecord($id);
        $deleteForm = $this->createDeleteForm($object);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Logs::class);
        $logs = $repo->getLogEntries($object);
        /** @var RouteRepository $front_page_preview */
        $front_page_preview = $em->getRepository(PageRoute::class)->findSlugByPageName('Press Releases');
        return array(
            'logs' => $logs,
            'object' => $object,
            'delete_form' => $deleteForm->createView(),
            'front_page_preview' => $front_page_preview
        );
    }

    /**
     * Finds and Rever a activeProvince entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="admin_leasing_press-release_revert")
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
     * Displays a form to edit an existing PressRelease entity.
     *
     * @Route("/{id}/edit", name="admin_leasing_press-release_edit")
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
        return parent::editAction($request, $id, $formOptions);
    }

    /**
     * Displays a form to translate an existing PressRelease entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="admin_leasing_press-release_translate_to")
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
        return parent::translateAction($request, $id, $translateToLanguage, $formOptions);
    }

    /**
     * Lists all PressRelease entity translations.
     *
     * @Route("/{id}/translate", name="admin_leasing_press-release_translate")
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
     * Deletes a PressRelease entity.
     *
     * @Route("/{id}", name="admin_leasing_press-release_delete")
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
     * Apply transition
     *
     * @Route("/apply-transition/{id}/{transition}", name="admin_leasing_press-release_apply_transition")
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
     * @return PressReleaseRepository|ObjectRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(PressRelease::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return PressRelease
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new PressRelease() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType()
    {
        return 'CraftKeen\FCRBundle\Form\PressReleaseType';
    }
}
