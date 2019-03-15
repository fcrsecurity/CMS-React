<?php

namespace CraftKeen\CMS\MenuBundle\Controller\Admin;

use CraftKeen\Bundle\CacheBundle\Event\ClearCacheEvent;
use CraftKeen\CMS\MenuBundle\Entity\Menu;
use CraftKeen\CMS\MenuBundle\Entity\MenuType;
use CraftKeen\CMS\MenuBundle\Form\MenuType as MenuFormType;
use CraftKeen\CMS\PageBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;

/**
 * Menu controller.
 *
 * @Route("menu")
 */
class MenuController extends BaseCrudController
{
    /**
     * Lists all menu entities.
     *
     * @Route("/", name="craftkeen_cms_page_admin_menu_index")
     * @Method("GET")
     * @Template()
     *
     * {@inheritdoc}
     */
    public function indexAction(Request $request, $filters = [])
    {
        $this->get('security.token_storage');
        $currentLanguage = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();
        $findBy['lang'] = $currentLanguage;

        if ($request->query->get('filterBy') && is_array($request->query->get('filterBy'))) {
            foreach ($request->query->get('filterBy') as $key => $filter) {
                if (strlen($filter) > 0) {
                    $findBy[$key] = $filter;
                }
            }
            $paginationResults = $this->getDoctrine()
                ->getRepository(Menu::class)
                ->findBy($findBy);
        } else {
            $paginationResults = $this->getDoctrine()
                ->getRepository(Menu::class)
                ->findBy(['lang' => $currentLanguage]);
        }
        $paginator = $this->get('knp_paginator');
        $menus = $paginator->paginate(
            $paginationResults,
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/*limit per page*/
        );

        return [
            'pagination' => $menus,
            'filterBy' => [
                'type' => $this->getDoctrine()
                    ->getRepository(MenuType::class)->findAll(),
                'page' => $this->getDoctrine()
                    ->getRepository(Page::class)->findBy(['lang' => $currentLanguage]),
            ],
        ];
    }

    /**
     * Creates a new analystCoverage entity.
     *
     * @Route("/new", name="craftkeen_cms_page_admin_menu_new")
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
     * @Route("/{id}", name="craftkeen_cms_page_admin_menu_show")
     * @Method("GET")
     * @Template()
     *
     * {@inheritdoc}
     */
    public function showAction($id)
    {
        return parent::showAction($id);
    }

    /**
     * @Route("/{id}/revert/{version}", name="craftkeen_cms_page_admin_menu_revert")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     *
     * {@inheritdoc}
     */
    public function revertAction($id, $version)
    {
        return parent::revertAction($id, $version);
    }

    /**
     * @Route("/{id}/edit", name="craftkeen_cms_page_admin_menu_edit")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * {@inheritdoc}
     */
    public function editAction(Request $request, $id, $formOptions = [])
    {
        return parent::editAction($request, $id, $formOptions);
    }

    /**
     * @Route("/{id}/translate/{translateToLanguage}", name="craftkeen_cms_page_admin_menu_translate_to")
     *
     * @Method({"GET", "POST"})
     * @Template()
     *
     * {@inheritdoc}
     */
    public function translateAction(Request $request, $id, Language $translateToLanguage, $formOptions = [])
    {
        return parent::translateAction($request, $id, $translateToLanguage, $formOptions);
    }

    /**
     * @Route("/{id}/translate", name="craftkeen_cms_page_admin_menu_translate")
     * @Method("GET")
     * @Template()
     *
     * {@inheritdoc}
     */
    public function translateIndexAction(Request $request, $id)
    {
        return parent::translateIndexAction($request, $id);
    }

    /**
     * @Route("/{id}", name="craftkeen_cms_page_admin_menu_delete")
     * @Method("DELETE")
     *
     * {@inheritdoc}
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * @Route("/clear-cache/", name="craftkeen_cms_page_admin_menu_clear_cache")
     */
    public function clearCacheAction()
    {
        $this->get('event_dispatcher')->dispatch(ClearCacheEvent::CLEAR_EVENT, new ClearCacheEvent('menu'));

        return $this->redirectToRoute('craftkeen_cms_page_admin_menu_index');
    }

    /**
     * @Route("/apply-transition/{id}/{transition}", name="craftkeen_cms_page_admin_menu_apply_transition")
     * @Method("GET")
     *
     * {@inheritdoc}
     */
    public function applyTransitionAction(Request $request, $id)
    {
        return parent::applyTransitionAction($request, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Menu::class);
    }

    /**
     * {@inheritdoc}
     *
     * @return Menu
     */
    public function getNewEntity()
    {
        return new Menu();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityFormType()
    {
        return MenuFormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityBaseRoute()
    {
        return 'craftkeen_cms_page_admin_menu_';
    }
}
