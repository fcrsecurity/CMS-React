<?php

namespace CraftKeen\CMS\UserBundle\Controller;

use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\CMS\UserBundle\Form\UserType;
use CraftKeen\CMS\UserBundle\Form\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * User controller.
 *
 * @Route("admin/user")
 */
class AdminController extends Controller
{

    /**
     * Lists all user entities.
     *
     * @Route("/", name="craftkeen_cms_user_admin_user_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10 /*limit per page*/
        );

        return $this->render('CraftKeenCMSUserBundle:Admin:index.html.twig', [
            'pagination' => $users,
        ]);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="craftkeen_cms_user_admin_user_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->getDoctrine()
                ->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ( !$currentUser ) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Profile created');
                return $this->redirectToRoute('craftkeen_cms_user_admin_user_show', ['id' => $user->getId()]);
            } else {
                $this->addFlash('danger', 'Profile with such email exists!');
            }
        }

        return $this->render('CraftKeenCMSUserBundle:Admin:new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="craftkeen_cms_user_admin_user_show")
     * @Method("GET")
     * @param User $user
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        if (false === $this->isGranted('ROLE_ADMINISTRATOR')) {
            if ($this->get('security.token_storage')->getToken()->getUser()->getId() !== $user->getId()) {
                throw new AccessDeniedException();
            }
        }

        return $this->render('CraftKeenCMSUserBundle:Admin:show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="craftkeen_cms_user_admin_user_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @param Request $request
     * @param User $user
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm(UserEditType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Profile updated');

            return $this->redirectToRoute('craftkeen_cms_user_admin_user_edit', ['id' => $user->getId()]);

        }

        return $this->render('CraftKeenCMSUserBundle:Admin:edit.html.twig', [
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="craftkeen_cms_user_admin_user_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @param Request $request
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('craftkeen_cms_user_admin_user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('craftkeen_cms_user_admin_user_delete', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
