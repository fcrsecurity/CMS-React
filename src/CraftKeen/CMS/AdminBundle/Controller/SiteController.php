<?php

namespace CraftKeen\CMS\AdminBundle\Controller;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Site controller.
 *
 * @Route("site")
 */
class SiteController extends Controller
{

    /**
     * Lists all site entities.
     *
     * @Route("/", name="craftkeen_cms_admin_site_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('view');

        $paginator = $this->get('knp_paginator');
        $sites = $paginator->paginate(
            $this->getDoctrine()
            ->getRepository('CraftKeenCMSAdminBundle:Site')->findAll(),  /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            ($request->query->get('per_page')) ? (int) $request->query->get('per_page') : 10/* limit per page */
        );
        
        return $this->render('CraftKeenCMSAdminBundle:Site:index.html.twig', [
                'pagination' => $sites,
        ]);
    }

    /**
     * Creates a new site entity.
     *
     * @Route("/new", name="craftkeen_cms_admin_site_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $site = new Site();
        $form = $this->createForm('CraftKeen\CMS\AdminBundle\Form\SiteType', $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();

            return $this->redirectToRoute('craftkeen_cms_admin_site_show', ['id' => $site->getId()]);
        }

        return $this->render('CraftKeenCMSAdminBundle:Site:new.html.twig', [
                    'site' => $site,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a site entity.
     *
     * @Route("/{id}", name="craftkeen_cms_admin_site_show")
     * @Method("GET")
     */
    public function showAction(Site $site)
    {
        $deleteForm = $this->createDeleteForm($site);

        return $this->render('CraftKeenCMSAdminBundle:Site:show.html.twig', [
                    'site' => $site,
                    'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing site entity.
     *
     * @Route("/{id}/edit", name="craftkeen_cms_admin_site_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Site $site)
    {
        $deleteForm = $this->createDeleteForm($site);
        $editForm = $this->createForm('CraftKeen\CMS\AdminBundle\Form\SiteType', $site);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('craftkeen_cms_admin_site_edit', ['id' => $site->getId()]);
        }

        return $this->render('CraftKeenCMSAdminBundle:Site:edit.html.twig', [
                    'site' => $site,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a site entity.
     *
     * @Route("/{id}", name="craftkeen_cms_admin_site_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Site $site)
    {
        $form = $this->createDeleteForm($site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($site);
            $em->flush();
        }

        return $this->redirectToRoute('craftkeen_cms_admin_site_index');
    }

    /**
     * Creates a form to delete a site entity.
     *
     * @param Site $site The site entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Site $site)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('craftkeen_cms_admin_site_delete', ['id' => $site->getId()]))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }
}
