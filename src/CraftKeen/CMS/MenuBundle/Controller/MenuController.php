<?php

namespace CraftKeen\CMS\MenuBundle\Controller;

use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\Route as PageRoute;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    /**
     * @Route("/getAdminMenu", name="craftkeen_cms_page_admin_menu" )
     * @param $route
     * @param $slug
     * @param Request $request
     *
     * @return Response
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getAdminMenuAction($route, $slug, Request $request)
    {
        $theme = $this->getParameter('theme_name');
        /** @var Page $pageCopy */
        $pageCopy = null;
        $canDelete = false;
        $hasDraft = false;

        $editMode = $request->get('editMode');
        $approveMode = $request->get('approveMode');

        if ('Y' == $editMode) {
            $editMode = true;
        } else {
            $editMode = false;
        }

        if ('Y' == $approveMode) {
            $approveMode = true;
        } else {
            $approveMode = false;
        }

        /** @var EntityRepository $repository */
        $routeObj = $this->getDoctrine()->getRepository(PageRoute::class)
            ->findBySlugAndDomain($slug, $request->getHost());

        if (!is_null($routeObj)) {
            $page = $routeObj->getPage();

            if ($page->getLang() !== $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()) {
                /* Find Translation by langParent */
                $translatedPage = $this->getDoctrine()->getRepository(Page::class)->findOneByLangParent($page);

                if (!is_null($translatedPage)) {
                    $page = $translatedPage;
                }
            }
            /* Try to Find copy of the page */
            $pageCopy = $this->getDoctrine()->getRepository(Page::class)
                ->findOneBy(['copyOf' => $page]);
        } else {
            $page = null;
            $pageCopy = null;
        }

        $isCurrentUser = !is_null($pageCopy) && ($pageCopy->getCreatedBy() == $this->getUser());
        $isDraft = !is_null($pageCopy) && 'draft' == $pageCopy->getStatus();

        if (($isDraft && $isCurrentUser && $editMode) || ($isDraft && $this->isGranted('candelete', $pageCopy))) {
            $canDelete = true;
        }

        // Try to find if current page has a draft
        if (null !== $pageCopy && 'draft' == $pageCopy->getStatus()) {
            $hasDraft = true;
        }

        return $this->render(
            'CraftKeenCMSThemeBundle:' . $theme . ':' . 'admin_menu.html.twig',
            [
                'route' => $route,
                'page' => $page,
                'pageCopy' => $pageCopy,
                'editMode' => $editMode,
                'approveMode' => $approveMode,
                'pathInfo' => $request->getPathInfo(),
                'canDelete' => $canDelete,
                'hasDraft' => $hasDraft,
            ]
        );
    }
}
