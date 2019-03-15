<?php

namespace CraftKeen\BrochureBuilderBundle\Controller;

use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\BrochureBuilderBundle\Form\UserType;
use CraftKeen\BrochureBuilderBundle\Form\UserEditType;
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
 * @Route("brochure/user")
 * @Security("has_role('ROLE_BROCHURE_ADMINISTRATOR')")
 */
class AdminController extends Controller
{

    /**
     * Lists all user entities.
     *
     * @Route("/", name="brochure_admin_user_index")
     * @Method("GET")
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
                ->findBrochureUsers(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10 /*limit per page*/
        );

        return $this->render('BrochureBuilderBundle:Admin:index.html.twig', [
            'pagination' => $users,
        ]);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="brochure_admin_user_new")
     * @Method({"GET", "POST"})
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
                return $this->redirectToRoute('brochure_admin_user_show', ['id' => $user->getId()]);
            } else {
                $this->addFlash('danger', 'Profile with such email exists!');
            }
        }

        return $this->render('BrochureBuilderBundle:Admin:new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="brochure_admin_user_show")
     * @Method("GET")
     * @param User $user
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        return $this->render('BrochureBuilderBundle:Admin:show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="brochure_admin_user_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param User $user
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, User $user)
    {
        $roles = array_filter($user->getRoles(), function($role) {
            return $role !== User::ROLE_BROCHURE_EDITOR && $role !== User::ROLE_BROCHURE_APPROVER;
        });

        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm(UserEditType::class, $user);
        $editForm->handleRequest($request);

        $newRoles = array_unique(array_merge($roles, $user->getRoles()));
        $user->setRoles($newRoles);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Profile updated');

            return $this->redirectToRoute('brochure_admin_user_edit', ['id' => $user->getId()]);

        }

        return $this->render('BrochureBuilderBundle:Admin:edit.html.twig', [
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="brochure_admin_user_delete")
     * @Method("DELETE")
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

        return $this->redirectToRoute('brochure_admin_user_index');
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
            ->setAction($this->generateUrl('brochure_admin_user_delete', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
