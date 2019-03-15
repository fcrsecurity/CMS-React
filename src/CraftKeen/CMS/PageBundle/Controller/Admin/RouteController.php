<?php

namespace CraftKeen\CMS\PageBundle\Controller\Admin;

use CraftKeen\CMS\PageBundle\Entity\Route as RouteEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route controller.
 *
 * @Route("route")
 */
class RouteController extends Controller
{
    /**
     * Lists all route entities.
     *
     * @Route("/", name="craftkeen_cms_page_admin_route_index")
     * @Method("GET")
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $routes = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(\CraftKeen\CMS\PageBundle\Entity\Route::class)
                ->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/*limit per page*/
        );

        return $this->render('CraftKeenCMSPageBundle:Admin:Route/index.html.twig', [
            'pagination' => $routes,
        ]);
    }

    /**
     * Creates a new route entity.
     *
     * @Route("/new", name="craftkeen_cms_page_admin_route_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $route = new RouteEntity();
        $form = $this->createForm('CraftKeen\CMS\PageBundle\Form\RouteType', $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($route);
            $em->flush();

            return $this->redirectToRoute('craftkeen_cms_page_admin_route_show', ['id' => $route->getId()]);
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Route/new.html.twig', [
            'route' => $route,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a route entity.
     *
     * @Route("/{id}", name="craftkeen_cms_page_admin_route_show")
     * @Method("GET")
     * @param RouteEntity $route
     *
     * @return Response
     */
    public function showAction(RouteEntity $route)
    {
        $deleteForm = $this->createDeleteForm($route);

        return $this->render('CraftKeenCMSPageBundle:Admin:Route/show.html.twig', [
            'route' => $route,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing route entity.
     *
     * @Route("/{id}/edit", name="craftkeen_cms_page_admin_route_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param RouteEntity $route
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, RouteEntity $route)
    {
        $deleteForm = $this->createDeleteForm($route);
        $editForm = $this->createForm('CraftKeen\CMS\PageBundle\Form\RouteType', $route);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('craftkeen_cms_page_admin_route_edit', ['id' => $route->getId()]);
        }

        return $this->render('CraftKeenCMSPageBundle:Admin:Route/edit.html.twig', [
            'route' => $route,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a route entity.
     *
     * @Route("/{id}", name="craftkeen_cms_page_admin_route_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param RouteEntity $route
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, RouteEntity $route)
    {
        $form = $this->createDeleteForm($route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($route);
            $em->flush();
        }

        return $this->redirectToRoute('craftkeen_cms_page_admin_route_index');
    }

    /**
     * Creates a form to delete a route entity.
     *
     * @param RouteEntity $route The route entity
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(RouteEntity $route)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('craftkeen_cms_page_admin_route_delete', ['id' => $route->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
