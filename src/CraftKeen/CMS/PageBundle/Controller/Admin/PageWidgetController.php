<?php

namespace CraftKeen\CMS\PageBundle\Controller\Admin;

use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\CMS\AdminBundle\Entity\Inbox;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Pagewidget controller.
 *
 * @Route("pagewidget")
 */
class PageWidgetController extends Controller
{
    /**
     * Lists all pageWidget entities.
     *
     * @Route("/", name="craftkeen_cms_page_admin_pagewidget_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $currentLanguage = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();
        $findBy = [
            'copyOf' => null
        ];
        if ($request->query->get('filterBy') && is_array($request->query->get('filterBy'))) {
            foreach ($request->query->get('filterBy') as $key => $filter) {
                if (strlen($filter) > 0) {
                    $findBy[$key] = $filter;
                }
            }
        }
        $paginationResults = $this->getDoctrine()->getRepository(PageWidget::class)
            ->findBy($findBy);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $paginationResults, $request->query->getInt('page', 1), /* page number */ ($request->query->get('per_page')) ? (int) $request->query->get('per_page') : 10/* limit per page */
        );
//        $findBy = $this->getDoctrine()->getRepository(PageWidget::class)->allPageWidget($lang);
//
//        $paginator = $this->get('knp_paginator');
//        $pageWidgets = $paginator->paginate(
//            $findBy, /* query NOT result */
//            $request->query->getInt('page', 1), /*page number*/
//            ($request->query->get('per_page')) ? (int) $request->query->get('per_page') : 10/*limit per page*/
//        );

        $filters['page'] = $this->getDoctrine()->getRepository(Page::class)->findBy([
            'lang' => $currentLanguage,
            'status' => 'live'
        ],
        ['name' => 'ASC']);
        
        return $this->render('CraftKeenCMSPageBundle:Admin:Pagewidget/index.html.twig', [
            'pageWidgets' => $pagination,
            'filters' => $filters
        ]);
    }

    /**
     * Creates a new pageWidget entity.
     *
     * @Route("/new", name="craftkeen_cms_page_admin_pagewidget_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->isGranted('cancreate', new PageWidget)) {
            return $this->redirectToRoute('craftkeen_cms_page_admin_pagewidget_index');
        }
        $pageWidget = new Pagewidget();
        $form = $this->createForm('CraftKeen\CMS\PageBundle\Form\PageWidgetType', $pageWidget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageWidget->setStatus('draft');
            $em = $this->getDoctrine()->getManager();
            $em->persist($pageWidget);
            $em->flush();

            return $this->redirectToRoute('craftkeen_cms_page_admin_pagewidget_show', ['id' => $pageWidget->getId()]);
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Pagewidget/new.html.twig', [
            'pageWidget' => $pageWidget,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a pageWidget entity.
     *
     * @Route("/{id}", name="craftkeen_cms_page_admin_pagewidget_show")
     * @Method("GET")
     */
    public function showAction(PageWidget $pageWidget)
    {
        $this->denyAccessUnlessGranted('canview', $pageWidget);

        $deleteForm = $this->createDeleteForm($pageWidget);

        return $this->render('CraftKeenCMSPageBundle:Admin:Pagewidget/show.html.twig', [
            'pageWidget' => $pageWidget,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing pageWidget entity.
     *
     * @Route("/{id}/edit", name="craftkeen_cms_page_admin_pagewidget_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PageWidget $pageWidget)
    {
        $this->denyAccessUnlessGranted('canedit', $pageWidget);

        $deleteForm = $this->createDeleteForm($pageWidget);
        $editForm = $this->createForm('CraftKeen\CMS\PageBundle\Form\PageWidgetType', $pageWidget);
        $editForm->handleRequest($request);

        $id = $pageWidget->getId();
        $copy = $this->getDoctrine()->getRepository(PageWidget::class)->findOneBy(['copyOf' => $id]);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($pageWidget->getStatus() == 'live') {
                if (!is_null($copy)) {
                    $copy->setPage($pageWidget->getPage());
                    $copy->setWidget($pageWidget->getWidget());
                    $copy->setConfig($pageWidget->getConfig());
                    $copy->setData($pageWidget->getData());
                    $copy->setDataType($pageWidget->getDataType());
                    $copy->setTplArea($pageWidget->getTplArea());
                    $copy->setStatus('draft');
                    $copy->setCopyOf($pageWidget);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($copy);
                    $em->flush($copy);

                    $id = $copy->getId();
                } else {
                    $new = new PageWidget();

                    $new->setPage($pageWidget->getPage());
                    $new->setWidget($pageWidget->getWidget());
                    $new->setConfig($pageWidget->getConfig());
                    $new->setData($pageWidget->getData());
                    $new->setDataType($pageWidget->getDataType());
                    $new->setTplArea($pageWidget->getTplArea());
                    $new->setStatus('draft');
                    $new->setCopyOf($pageWidget);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($new);
                    $em->flush($new);

                    $id = $new->getId();
                }
            } else {
                $this->getDoctrine()->getManager()->flush();
            }

            if ($request->get('transition')) {
                if ($request->get('transition') == 'to_preview') {
                    return $this->redirectToRoute('craftkeen_cms_page_admin_pagewidget_preview', [
                        'id' => $id
                    ]);
                } else {
                    return $this->redirectToRoute('craftkeen_cms_page_admin_pagewidget_apply_transition', [
                        'id' => $id,
                        'transition' => $request->get('transition')
                    ]);
                }
            } else {
                return $this->redirectToRoute('craftkeen_cms_page_admin_pagewidget_edit', ['id' => $id]);
            }

            return $this->redirectToRoute('craftkeen_cms_page_admin_pagewidget_edit', ['id' => $pageWidget->getId()]);
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Pagewidget/edit.html.twig', [
            'pageWidget' => $pageWidget,
            'pageWidgetCopy' => $copy,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a pageWidget entity.
     *
     * @Route("/{id}", name="craftkeen_cms_page_admin_pagewidget_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PageWidget $pageWidget)
    {
        $this->denyAccessUnlessGranted('candelete', $pageWidget);

        $form = $this->createDeleteForm($pageWidget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pageWidget);
            $em->flush();
        }

        return $this->redirectToRoute('craftkeen_cms_page_admin_pagewidget_index');
    }

    /**
     * Creates a form to delete a pageWidget entity.
     *
     * @param PageWidget $pageWidget The pageWidget entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PageWidget $pageWidget)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('craftkeen_cms_page_admin_pagewidget_delete', ['id' => $pageWidget->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/apply-transition/{id}/{transition}", name="craftkeen_cms_page_admin_pagewidget_apply_transition")
     * @Method("GET")
     */
    public function applyTransitionAction(Request $request, PageWidget $pageWidget)
    {
        $transition = $request->get('transition');
        $id = $pageWidget->getId();
        try {
            $this->get('workflow.widget_publishing')->apply($pageWidget, $transition);

            if ($transition == 'publish') {
                $sourcePageWidget = $pageWidget->getCopyOf();

                if (is_null($sourcePageWidget)) {
                    $this->get('doctrine')->getManager()->flush();
                } else {
                    // Copy edited data to source page
                    $sourcePageWidget = $this->getDoctrine()->getRepository(PageWidget::class)->findOneBy(['id' => $sourcePageWidget]);
                    $sourcePageWidget->setPage($pageWidget->getPage());
                    $sourcePageWidget->setWidget($pageWidget->getWidget());
                    $sourcePageWidget->setConfig($pageWidget->getConfig());
                    $sourcePageWidget->setData($pageWidget->getData());
                    $sourcePageWidget->setDataType($pageWidget->getDataType());
                    $sourcePageWidget->setTplArea($pageWidget->getTplArea());
                    $sourcePageWidget->setStatus('live');

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sourcePageWidget);
                    $em->flush($sourcePageWidget);

                    // Delete copied pageWidget
                    $em->remove($pageWidget);
                    $em->flush($pageWidget);

                    $id = $sourcePageWidget->getId();
                }
            } else {
                $this->get('doctrine')->getManager()->flush();
            }

            if ($transition == 'to_review') {
                // Send messages to all approvers
                $approvers = $this->getDoctrine()->getRepository(User::class)->findUserByRole('ROLE_APPROVER');

                foreach ($approvers as $approver) {
                    $inbox = new Inbox;
                    $inbox->setSender($pageWidget->getPage()->getUpdatedBy()->getUsername());
                    $inbox->setSubject('Incoming widget for approve');
                    $inbox->setRecipient($approver);
                    $inbox->setMessage(sprintf(
                        'Widget <a href="/admin/pagewidget/%d" target="_blank">"%s"</a> needs to approve.',
                        $pageWidget->getId(),
                        $pageWidget->getDataType()
                    ));
                    $inbox->setIsRead(false);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inbox);
                    $em->flush($inbox);
                }
            }
        } catch (ExceptionInterface $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('craftkeen_cms_page_admin_pagewidget_show', ['id' => $id])
        );
    }

    /**
     * Finds and displays a page entity.
     *
     * @Route("/preview/{id}", name="craftkeen_cms_page_admin_pagewidget_preview", defaults={"id":"0"},)
     * @Method("GET")
     */
    public function previewAction(PageWidget $pageWidget)
    {
        $this->denyAccessUnlessGranted('canview', $pageWidget);

        $widgets  = [];
        $page = $pageWidget->getPage();
        $widgets = $this->getDoctrine()->getRepository(PageWidget::class)->findWidgetsByPage($page);

        foreach ($widgets as $key => $widget) {
            $widgets[$key]['config'] = unserialize($widget['config']);
            $widgets[$key]['data'] = unserialize($widget['data']);
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Pagewidget/preview.html.twig', [
            'page' => $page,
            'widgets' => $widgets,
            'pageWidget' => $pageWidget,
        ]);
    }
}
