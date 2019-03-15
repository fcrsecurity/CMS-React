<?php

namespace CraftKeen\CMS\PageBundle\Controller;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\PageBundle\Entity\Route as PageRoute;
use Doctrine\ORM\EntityManager;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/pageinline-apply-transition/{id}/{transition}", name="craftkeen_cms_page_apply_transition")
     * @Method("GET")
     * @param Request $request
     * @param Page $page
     *
     * @return string|RedirectResponse
     */
    public function applyTransitionAction(Request $request, Page $page)
    {
        $transition = $request->get('transition');
        $returnUrl = $request->server->get('HTTP_REFERER');
        $versionComment = $request->get('versionComment');

        try {
            if ($transition == 'publish') {
                $search = ['/editMode'];
                $replace = [''];
                $returnUrl = str_replace($search, $replace, $returnUrl);
            }

            if (strlen($versionComment) > 0) {
                $page->setVersionComment($versionComment);
            }
            $this->get('workflow.page_publishing')->apply($page, $transition);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect($returnUrl);
    }

    /**
     * @Route("/delete-draft/{id}", name="craftkeen_cms_page_delete_draft")
     * @Method("GET")
     *
     * @param Page $page
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteDraftAction(Page $page, Request $request)
    {
        $referer = $request->headers->get('referer');
        $referer = str_replace('/editMode', '', $referer);

        $em = $this->getDoctrine()->getManager();

        if ($this->isGranted('candelete', $page)) {
            // TODO: This code removes entire record from database ignoring SoftDeletable filter.
            // We need to implement this in more nice way and make, in case we need re-use it.
            $originalEventListeners = [];
            // cycle through all registered event listeners
            foreach ($em->getEventManager()->getListeners() as $eventName => $listeners) {
                foreach ($listeners as $listener) {
                    if ($listener instanceof SoftDeleteableListener) {
                        // store the event listener, that gets removed
                        $originalEventListeners[$eventName] = $listener;
                        // remove the SoftDeletableSubscriber event listener
                        $em->getEventManager()->removeEventListener($eventName, $listener);
                    }
                }
            }

            $em->remove($page);
            $em->flush();

            // re-add the removed listener back to the event-manager
            foreach ($originalEventListeners as $eventName => $listener) {
                $em->getEventManager()->addEventListener($eventName, $listener);
            }
        }

        return $this->redirect($referer);
    }

    /**
     * @Route("/editMode", name="craftkeen_cms_page_index_edit", defaults={"slug":"/"})
     * @Route("/{slug}/editMode", name="craftkeen_cms_page_inner_edit", requirements={"slug": ".+"})
     *
     * @Template()
     *
     * @param $slug
     *
     * @return array|Response
     *
     */
    public function editModeAction($slug)
    {
        // Load Site Manager
        $siteManager = $this->container->get('craft_keen_cms.site_manager');
        /** @var Site $site */
        $site = $siteManager->getCurrentSite();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $localeId = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();

        $theme = $this->getParameter('theme_name');
        if (strlen($site->getTheme()) > 0) {
            $theme = $site->getTheme();
        }

        /** @var PageRoute $route */
        $route = $this->getDoctrine()->getRepository(PageRoute::class)->findBySlugAndSite($slug, $site);

        if ($route != null) {
            /** @var Page $page */
            $page = $route->getPage();
            $page = $this->get('craft_keen.translation.registry')->translate($page, $localeId);

            $pageCopy = $em->getRepository(Page::class)->findOneByCopyOf($page);

            $canApprove = $this->isGranted('canapprove', $pageCopy);

            if ($this->isGranted('canedit', $page) || $canApprove) {
                $pageEditor = $this->container->get('page.inline_editor');

                /* Check if page has translation, if not - create one */
                $translatedPage = null;
                if ($page->getLang() !== $localeId) {
                    // TODO: to check whether the translation is created or returned copy
                    $translatedPage = $pageEditor->createPageCopy($page, $this->getUser(), false);
                    $translatedPage->setLang($localeId);
                    $translatedPage->setLangParent($page);
                    $translatedPage->setStatus('live'); /* You don't need to send automatic translation to workflow. */
                    $em->flush($translatedPage);
                    $page = $translatedPage;
                }

                if ($this->isGranted('canedit', $page)) {
                    /** @var Page $pageCopy */
                    $pageCopy = $pageEditor->createPageCopy($page, $this->getUser());
                }

                $template = 'page';

                if ($pageCopy->getTemplate()) {
                    $template = $pageCopy->getTemplate();
                }

                $widgets = $this->getDoctrine()->getRepository(PageWidget::class)->findWidgetsByPage($pageCopy);

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

                return $this->render('CraftKeenCMSThemeBundle:' . $theme . ':' . $template . '.html.twig', [
                    'page' => $pageCopy,
                    'copy' => $pageCopy,
                    'widgets' => $widgets,
                    'site' => $site,
                    'mode' => 'edit'
                ]);
            } else {
                return $this->redirect('/'.$slug);
            }
        } else {
            throw $this->createNotFoundException();
        }
    }

    /**
     * @Route("/", name="craftkeen_cms_page_index", defaults={"slug":"/"})
     * @Route("/{slug}", name="craftkeen_cms_page_inner", requirements={"slug": ".+"})
     *
     * @Template()
     *
     * @param $slug
     *
     * @return array|Response
     */
    public function indexAction($slug)
    {
        // Load Site Manager
        $siteManager = $this->container->get('craft_keen_cms.site_manager');
        /** @var Site $site */
        $site = $siteManager->getCurrentSite();

        $localeId = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();

        $theme = $this->getParameter('theme_name');
        if (strlen($site->getTheme()) > 0) {
            $theme = $site->getTheme();
        }

        /** @var PageRoute $route */
        $route = $this->getDoctrine()->getRepository(PageRoute::class)->findBySlugAndSite($slug, $site);

        if ($route != null) {
            /** @var Page $page */
            $page = $route->getPage();
            $page = $this->get('craft_keen.translation.registry')->translate($page, $localeId);
            // Show pages with status 'live' only
            if ($page->getStatus() == 'live') {
                $template = 'page';

                if ($page->getTemplate()) {
                    $template = $page->getTemplate();
                }

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

                $page = $this->get('craft_keen.page.provider.revision')->getCurrentVersion($page);
                $page = $this->get('craft_keen.translation.registry')->translate($page, $localeId);

                $copy = $this->getDoctrine()->getRepository(Page::class)->findOneByCopyOf($page);

                return $this->render('CraftKeenCMSThemeBundle:' . $theme . ':' . $template . '.html.twig', [
                    'page' => $page,
                    'copy' => $copy,
                    'widgets' => $widgets,
                    'site' => $site,
                    'mode' => 'view',
                ]);
            } else {
                throw $this->createNotFoundException('404. Page not found! Page not approved');
            }
        } else {
            throw $this->createNotFoundException();
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function inlineEditorHandlerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            // Create Logged instance
            $logger = $this->get('logger');

            // Prepare default response
            $message = 'Unknown Action';

            // Gather Request Data
            $postRequest = $request->request->get('page');

            // Load Page Editor Service
            $pageEditor = $this->container->get('page.inline_editor');

            $response = ['success' => false,];
            // handle POST Requests
            if (isset($postRequest['action'])) {
                switch ($postRequest['action']) {
                    case 'savePageChanges':
                        if (isset($postRequest['id']) &&
                            isset($postRequest['layout']) &&
                            isset($postRequest['author'])
                        ) {
                            if ($pageEditor->updateLayout($postRequest)) {
                                $message = sprintf(
                                    "Page ID: %d Layout Successfully Saved by user with ID: %s",
                                    $postRequest['id'],
                                    $postRequest['author']
                                );
                                $logger->info($message);
                                $response['success'] = true;
                            } else {
                                $message = "Cannot save Page Layout";
                                $logger->error($message);
                            }
                        }
                        break;

                    case 'savePageWidgetContent':
                        if (isset($postRequest['id']) &&
                            isset($postRequest['widgets']) &&
                            isset($postRequest['author'])
                        ) {
                            if ($pageEditor->updatePageWidgetContent($postRequest)) {
                                $message = sprintf(
                                    "Page ID: %d Page Widgets Successfully Saved by user with ID: %d",
                                    $postRequest['id'],
                                    $postRequest['author']
                                );
                                $logger->info($message);
                                $response['success'] = true;
                            } else {
                                $message = "Cannot save Page Widget";
                                $logger->error($message);
                            }
                        }
                        break;
                }
            }

            // Format Response message
            $response['message'] = $message;

            return new JsonResponse(
                $response
            );
        }

        return new Response('This is not ajax!', 400);
    }
}
