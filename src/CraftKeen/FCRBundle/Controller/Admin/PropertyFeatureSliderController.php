<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\FCRBundle\Entity\PropertyFeatureSlider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\CMS\PageBundle\Entity\Page;

/**
 * PropertyFeatureSliderController
 *
 * @Route("admin/leasing/property/feature-slider")
 */
class PropertyFeatureSliderController extends BaseCrudController
{
    use LeasingPermissionsTrait;

    /**
     * Lists all propertyFeatureSlider entities.
     *
     * @Route("/", name="admin_leasing_property_feature-slider_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     * @return array
     */
    public function indexAction(Request $request, $filters = [])
    {
        $currentLanguage = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();
        $findBy['lang'] =  $currentLanguage;
        if ($request->query->get('filterBy') && is_array($request->query->get('filterBy'))) {
            foreach ($request->query->get('filterBy') as $key => $filter) {
                if (strlen($filter) > 0 ) {
                    $findBy[$key] = $filter;
                }
            }
            $paginationResults = $this->getDoctrine()
                ->getRepository(PropertyFeatureSlider::class)
                ->findBy($findBy);
        } else {
             $paginationResults = $this->getDoctrine()
                ->getRepository(PropertyFeatureSlider::class)
                ->findBy(['lang' => $currentLanguage]);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $paginationResults,
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/*limit per page*/
        );
        return [
            'pagination' => $pagination,
            'filterBy' => [
                'page' => $this->getDoctrine()
                        ->getRepository(Page::class)
                        ->findBy(
                            [
                            'lang' => $this->get('craft_keen.translation.provider.language')->getCurrentLanguage(),
                            'status' => 'live'
                            ],
                            ['name' => 'ASC'])
            ]
        ];
    }

     /**
     * Creates a new analystCoverage entity.
     *
     * @Route("/new", name="admin_leasing_property_feature-slider_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, $formOptions = [])
    {
        return parent::newAction($request, []);
    }

    /**
     * Finds and displays a analystCoverage entity.
     *
     * @Route("/{id}", name="admin_leasing_property_feature-slider_show")
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
     * @Route("/{id}/revert/{version}", name="admin_leasing_property_feature-slider_revert")
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
     * Displays a form to edit an existing analystCoverage entity.
     *
     * @Route("/{id}/edit", name="admin_leasing_property_feature-slider_edit")
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
	 * @Route("/{id}/translate/{translateToLanguage}", name="admin_leasing_property_feature-slider_translate_to")
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
	 * @Route("/{id}/translate", name="admin_leasing_property_feature-slider_translate")
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
     * @Route("/{id}", name="admin_leasing_property_feature-slider_delete")
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
     * @Route("/apply-transition/{id}/{transition}", name="admin_leasing_property_feature-slider_apply_transition")
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
     * @return \CraftKeen\FCRBundle\Repository\PropertyFeatureSliderRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(PropertyFeatureSlider::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return PropertyFeatureSlider
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new PropertyFeatureSlider() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\PropertyFeatureSliderType';
    }
}
