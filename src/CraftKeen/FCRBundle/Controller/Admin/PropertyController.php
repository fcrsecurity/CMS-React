<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\Property;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * Property controller.
 *
 * @Route("admin/leasing/property")
 */
class PropertyController extends BaseCrudController
{
    use LeasingPermissionsTrait;

    /**
     * Lists all property entities.
     *
     * @Route("/", name="craftkeen_fcr_admin_leasing_property_index")
     * @Method("GET")
     * @Template()
     *
     * @param Request $request
     * @param array $filters
     *
     * @return array
     */
    public function indexAction(Request $request, $filters = [])
    {
        $currentLanguage = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();
        $findBy = [
            'lang' => $currentLanguage,
            'copyOf' => null,
        ];

        $findBy['lang'] = $currentLanguage;
        if ($request->query->get('filterBy') && is_array($request->query->get('filterBy'))) {
            foreach ($request->query->get('filterBy') as $key => $filter) {
                if (strlen($filter) > 0) {
                    $findBy[$key] = $filter;
                }
            }
            $paginationResults = $this->getRepository()
                ->findBy($findBy, ['sortOrder' => 'ASC']);
        } else {
            $paginationResults = $this->getRepository()
                ->findBy(['lang' => $currentLanguage], ['sortOrder' => 'ASC']);
        }

        foreach ($paginationResults as &$paginationResult) {
            if ($paginationResult->getIsHidden()) {
                $paginationResult->setStatus('hidden');
            }
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $paginationResults,
            $request->query->getInt('page', 1), /* page number */
            (int)$request->query->get('per_page') ?: 10/* limit per page */
        );

        return [
            'pagination' => $pagination,
            'filterBy' => $filters,
        ];
    }

    /**
     * Creates a new Property entity.
     *
     * @Route("/new", name="craftkeen_fcr_admin_leasing_property_new")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request $request
     * @param array $formOptions
     *
     * @return array
     */
    public function newAction(Request $request, $formOptions = [])
    {
        return parent::newAction($request, $formOptions);
    }

    /**
     * Finds and displays a Property entity.
     *
     * @Route("/{id}", name="craftkeen_fcr_admin_leasing_property_show")
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
     * Finds and Rever a Property entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="craftkeen_fcr_admin_leasing_property_revert")
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
     * Displays a form to edit an existing Property entity.
     *
     * @Route("/{id}/edit", name="craftkeen_fcr_admin_leasing_property_edit")
     * @Security("has_role('ROLE_EDITOR') or has_role('ROLE_ADMINISTRATOR') ")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request $request
     * @param $id
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, $id, $formOptions = [])
    {
        return parent::editAction($request, $id, $formOptions);
    }

    /**
     * Displays a form to translate an existing Property entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="craftkeen_fcr_admin_leasing_property_translate_to")
     *
     * @Method({"GET", "POST"})
     * @Template()
     *
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
     * Lists all Property entity translations.
     *
     * @Route("/{id}/translate", name="craftkeen_fcr_admin_leasing_property_translate")
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
     * Deletes a Property entity.
     *
     * @Route("/{id}", name="craftkeen_fcr_admin_leasing_property_delete")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("DELETE")
     *
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
     * Apply Workflow transition to Property.
     *
     * @Route("/apply-transition/{id}/{transition}", name="craftkeen_fcr_admin_leasing_property_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\PropertyRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Property::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return Property
     */
    public function getNewEntity()
    {
        return $this->setEntityDefaults( new Property() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType()
    {
        return 'CraftKeen\FCRBundle\Form\PropertyType';
    }
}
