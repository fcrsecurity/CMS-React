<?php

namespace CraftKeen\CMS\PageBundle\Controller\Admin;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\MenuBundle\Entity\Menu;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\PageBundle\Entity\Route as PageRoute;
use CraftKeen\CMS\PageBundle\Entity\Widget;
use CraftKeen\CMS\PageBundle\Repository\PageRepository;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Page controller.
 *
 * @Route("page")
 */
class PageController extends BaseCrudController
{
    /**
     * Lists all page entities.
     *
     * @Route("/", name="craftkeen_cms_page_admin_page_index")
     * @Method("GET")
     *
     * @param Request $request
     * @param array $filters
     *
     * @return Response
     */
    public function indexAction(Request $request, $filters = [])
    {
        $lang = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();

        $repository = $this->getDoctrine()->getRepository(Page::class);

        // Filter Pages by Site
        $filterSite = ($request->query->get('site') ? $request->query->get('site') : null);

        $paginator = $this->get('knp_paginator');
        $pages_paginator = $paginator->paginate(
            $repository->allPages($request->query->get('parent'), $filterSite, $lang), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/*limit per page*/
        );

        $page = null;
        if ($request->query->get('parent')) {
            $page = $repository->findOneBy(['id' => $request->query->get('parent')]);
        }

        $sites = $this->getDoctrine()->getRepository(Site::class)->findAll();

        return $this->render('CraftKeenCMSPageBundle:Admin:Page/index.html.twig', [
            'page' => $page,
            'sites' => $sites,
            'pagination' => $pages_paginator,
        ]);
    }

    /**
     * Creates a new page entity. Available two extra options of creating a new page, with pre-populated Language
     *
     * @Route("/new", name="craftkeen_cms_page_admin_page_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function newAction(Request $request, $formOptions = [])
    {
        if (!$this->isGranted('cancreate', new Page)) {
            return $this->redirectToRoute('craftkeen_cms_page_admin_page_index');
        }

        $formOptions['user'] = $this->getUser();

        $page = new Page();
        $form = $this->createForm('CraftKeen\CMS\PageBundle\Form\PageType', $page, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $lang = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();

            // TODO: make default layout more dynamic
            $defaultLayout = '{"R1":{"R1C1":{"id":"title-widget","class":"col-xs-10 col-xs-offset-1 col-sm-10 ' .
                'col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2",' .
                '"content":"[widget-title2-callback]"}},"R2":{"R2C1":{"id":"text-widget","class":"col-xs-10 ' .
                'col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 ' .
                'buffer-7-padding-bottom","content":"[widget-text-callback]"}}}';

            $page->setLang($lang);
            $page->setCreatedBy($this->getUser());
            $page->setUpdatedBy($this->getUser());
            $page->setCreated(new \DateTime());
            $page->setVersion(1);
            $page->setVersionComment('Initial');
            $page->setStatus('draft');
            $page->setLayout($defaultLayout);

            $em->persist($page);
            $em->flush();

            $slug = str_replace(' ', '-', mb_strtolower($page->getName()));
            $route = new PageRoute();
            $route->setPage($page);
            $route->setSlug($slug);

            $em->persist($route);
            $em->flush();

            return $this->redirectToRoute('craftkeen_cms_page_admin_page_show', ['id' => $page->getId()]);
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a page entity.
     *
     * @Route("/{id}", name="craftkeen_cms_page_admin_page_show")
     * @Method("GET")
     * @Template()
     *
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
     * @Route("/{id}/revert/{version}", name="craftkeen_cms_page_admin_page_revert")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     * @Template()
     *
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
     * Displays a form to edit an existing page entity.
     *
     * @Route("/{id}/edit", name="craftkeen_cms_page_admin_page_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id, $formOptions = [])
    {
        /** @var Page $page */
        $page = $this->getEntity($id);
        if (null === $page) {
            throw $this->createNotFoundException();
        }

        $formOptions['user'] = $this->getUser();

        $deleteForm = $this->createDeleteForm($page);
        $editForm = $this->createForm('CraftKeen\CMS\PageBundle\Form\PageType', $page, $formOptions);
        $editForm->handleRequest($request);

        $id = $page->getId();

        $pageForRoute = $page;

        if ($page->getCopyOf()) {
            $pageForRoute = $page->getCopyOf();
        }

        $routes = $this->getDoctrine()->getRepository(\CraftKeen\CMS\PageBundle\Entity\Route::class)->findBy(
            ['page' => $pageForRoute]
        );

        $translations = $this->getDoctrine()->getRepository(Page::class)->findBy(
            ['langParent' => $page]
        );

        $widgets = $this->getDoctrine()->getRepository(Widget::class)->findAll();

        /** @var Page $copy */
        $copy = $this->getDoctrine()->getRepository(Page::class)->findCopy($page);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $pageEditor = $this->container->get('page.inline_editor');

            if ('live' == $page->getStatus()) {
                // if copy of page is there, then copy new data in this page copy
                if (!is_null($copy)) {
                    /* Update Page Draft */
                    $copy->setName($page->getName());
                    $copy->setHeroTitle($page->getHeroTitle());
                    $copy->setHero($page->getHero());
                    $copy->setHeroVideo($page->getHeroVideo());
                    $copy->setStatus('draft');
                    $copy->setLayout($page->getLayout());
                    $copy->setMetaTitle($page->getMetaTitle());
                    $copy->setMetaDescription($page->getMetaDescription());
                    $copy->setMetaKeywords($page->getMetaKeywords());
                    $copy->setCreated($page->getCreated());
                    $copy->setUpdated(new \DateTime());
                    $copy->setVersion($page->getVersion());
                    $copy->setIsIndexed($page->getIsIndexed());
                    $copy->setTemplate($page->getTemplate());
                    $copy->setCopyOf($page);
                    $copy->setLang($page->getLang());
                    $copy->setLangParent($page->getLangParent());
                    $copy->setParent($page->getParent());
                    $copy->setCreatedBy($page->getCreatedBy());
                    $copy->setUpdatedBy($this->getUser());
                    $copy->setVersionComment($page->getVersionComment());
                    $copy->setSite($page->getSite());
                    $copy->setAccess($page->getAccess());

                    $em->persist($copy);
                    $em->flush();

                    $id = $copy->getId();
                    $this->addFlash('success', 'Page Revision updated');
                } else {
                    /* Create New Draft */
                    $pageCopy = $pageEditor->createPageCopy($page, $this->getUser());
                    $this->addFlash('success', 'New Draft Created');
                    $id = $pageCopy->getId();
                }
            } else {
                $this->getDoctrine()->getManager()->flush();
            }

            /* Workflow Transition Router */
            if ($request->get('transition')) {
                if ($request->get('transition') == 'to_preview') {
                    return $this->redirectToRoute('craftkeen_cms_page_admin_page_preview', [
                        'id' => $id,
                    ]);
                } else {
                    return $this->redirectToRoute('craftkeen_cms_page_admin_page_apply_transition', [
                        'id' => $id,
                        'transition' => $request->get('transition'),
                    ]);
                }
            } else {
                return $this->redirectToRoute('craftkeen_cms_page_admin_page_edit', ['id' => $id]);
            }
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Page/edit.html.twig', [
            'page' => $page,
            'routes' => $routes,
            'widgets' => $widgets,
            'pageCopy' => $copy,
            'translations' => $translations,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a page entity.
     *
     * @Route("/{id}", name="craftkeen_cms_page_admin_page_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param integer $id
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine();
        $page = $this->findRecord($id);

        $menus = $em->getRepository(Menu::class)->findBy(
            ['page' => $page]
        );

        $routes = $em->getRepository(PageRoute::class)->findBy(
            ['page' => $page]
        );

        if (!is_null($menus) && count($menus) > 0) {
            foreach ($menus as $menu) {
                // Delete all children
                $children = $em->getRepository(Menu::class)->findByParent($menu);
                if (count($children) > 0) {
                    foreach ($children as $child) {
                        $em->getManager()->remove($child);
                    }
                }
                $em->getManager()->remove($menu);
            }
            $em->getManager()->flush();
        }

        if (!is_null($routes) && count($routes) > 0) {
            foreach ($routes as $route) {
                $em->getManager()->remove($route);
            }
        }

        return parent::deleteAction($request, $id);
    }

    /**
     * Displays a form to translate an existing analystCoverage entity.
     *
     * @Route("/{id}/translate/{translateToLanguage}", name="craftkeen_cms_page_admin_page_translate_to")
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
        /** @var Page $langParent */
        $langParent = $this->findRecord($id);
        // Get Exsiting translation
        $translation = $this->getRepository()->findExistingTranslation($langParent, $translateToLanguage);

        $pageEditor = $this->container->get('page.inline_editor');

        if (null !== $translation) {
            /* Translation was found. Redirect to an edit page */
            return $this->redirectToRoute($this->getEntityBaseRoute() . 'edit', ['id' => $translation->getId()]);
        } else {
            $adding = true;
            // New Translation needs to be added
            /* Create New translation Draft */
            $translation = $pageEditor->createPageCopy($langParent, $this->getUser());

            $translation->setLang($translateToLanguage);
            $translation->setLangParent($langParent);
            $translation->setCopyOf(null);

            /* Create Translation form */
            $form = $this->createForm($this->getEntityFormType(), $translation, $formOptions);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            if ($adding) {
                $em->persist($translation);
                $this->addFlash('success', 'Translation created');
            } else {
                $this->addFlash('success', 'Translation updated');
            }
            $em->flush($translation);

            return $this->redirectToRoute($this->getEntityBaseRoute() . 'translate_to', [
                'id' => $langParent->getId(),
                'translateToLanguage' => $translateToLanguage->getId()
            ]);
        }
        return [
            'langParent' => $langParent,
            'object' => $translation,
            'form' => $form->createView(),
        ];
    }

    /**
     * Lists all analystCoverage entity translations.
     *
     * @Route("/{id}/translate", name="craftkeen_cms_page_admin_page_translate")
     * @Method("GET")
     * @Template()
     *
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
     * Finds and displays a page entity.
     *
     * @Route("/preview/{id}", name="craftkeen_cms_page_admin_page_preview", defaults={"id":"0"},)
     * @Method("GET")
     *
     * @param Page $page
     *
     * @return Response
     */
    public function previewAction(Page $page)
    {
        $this->denyAccessUnlessGranted('canview', $page);
        $widgets = $this->getDoctrine()->getRepository(PageWidget::class)->findWidgetsByPage($page);

        foreach ($widgets as $key => $widget) {
            switch ($widget['dataType']) {
                case 'entity':
                    $widgets[$key]['data'] = $widget['data'];
                    $widgets[$key]['config'] = unserialize($widget['config']);
                    break;

                default:
                    $widgets[$key]['data'] = unserialize($widget['data']);
                    $widgets[$key]['config'] = unserialize($widget['config']);
                    break;
            }
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Page/preview.html.twig', [
            'page' => $page,
            'widgets' => $widgets,
        ]);
    }

    /**
     * @Route("/apply-transition/{id}/{transition}", name="craftkeen_cms_page_admin_page_apply_transition")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse|NotFoundHttpException
     */
    public function applyTransitionAction(Request $request, $id)
    {
        /** @var Page $page */
        $page = $this->getEntity($id);
        if (false === $page) {
            return $this->createNotFoundException();
        }
        $transition = $request->get('transition');
        try {
            switch ($transition) {
                case 'publish':
                    if (null !== $page->getCopyOf()) {
                        $id = $page->getCopyOf()->getId();
                    }
                    break;
                case 'reject':
                    $rejectionComment = $request->get('rejectionComment');
                    if (null !== $rejectionComment) {
                        $rejectionComment = "\nRejected! Reason: $rejectionComment";
                        $page->setVersionComment($page->getVersionComment() . $rejectionComment);
                    }
                    break;
            }

            $this->get('workflow.page_publishing')->apply($page, $transition);
            $this->get('doctrine')->getManager()->flush();
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->redirect(
            $this->generateUrl('craftkeen_cms_page_admin_page_show', ['id' => $id])
        );
    }

    /**
     * @see BaseApiController::getRepository()
     * @return PageRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Page::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return Page
     */
    public function getNewEntity()
    {
        return new Page();
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType()
    {
        return 'CraftKeen\CMS\PageBundle\Form\PageType';
    }

    /**
     * @see BaseApiController::getEntityBaseRoute()
     * @return String
     */
    public function getEntityBaseRoute()
    {
        return 'craftkeen_cms_page_admin_page_';
    }
}
