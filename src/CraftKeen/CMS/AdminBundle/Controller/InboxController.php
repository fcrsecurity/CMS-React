<?php

namespace CraftKeen\CMS\AdminBundle\Controller;

use CraftKeen\CMS\AdminBundle\Entity\Inbox;
use CraftKeen\CMS\AdminBundle\Repository\InboxRepository;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inbox controller.
 *
 * @Route("inbox")
 */
class InboxController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="craftkeen_cms_admin_inbox_index")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('view');

        $user = $this->getUser();
        /** @var InboxRepository $repository */
        $repository = $this->getDoctrine()->getRepository('CraftKeenCMSAdminBundle:Inbox');

        if ($request->query->get('markAsRead')) {
            /** @var Inbox $notification */
            $notification = $repository->find((int)$request->query->get('markAsRead'));
            if (null !== $notification) {
                $notification->setIsRead(true);
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->flush($notification);
            }
        }

        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $repository->findInbox($user), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/*limit per page*/
        );

        $emptyForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('craftkeen_cms_admin_inbox_empty'))
            ->setMethod('DELETE')
            ->getForm()
            ->createView();

        return $this->render('CraftKeenCMSAdminBundle:Inbox:index.html.twig', [
            'pagination' => $users,
            'new_messages_count' => $this->getNewMessagesNumber(),
            'emptyForm' => $emptyForm
        ]);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="craftkeen_cms_admin_inbox_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMINISTRATOR');

        $inbox = new Inbox();
        $form = $this->createForm('CraftKeen\CMS\AdminBundle\Form\InboxType', $inbox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inbox->setIsRead(false);
            $inbox->setCreated(new \DateTime());
            $inbox->setSender($this->get('security.token_storage')->getToken()->getUser()->getEmail());
            $em = $this->getDoctrine()->getManager();
            $em->persist($inbox);
            $em->flush();

            return $this->redirectToRoute('craftkeen_cms_admin_inbox_index');
        }

        return $this->render('CraftKeenCMSAdminBundle:Inbox:new.html.twig', [
            'inbox' => $inbox,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="craftkeen_cms_admin_inbox_show")
     * @Method("GET")
     * @param Inbox $inbox
     * @return Response
     */
    public function showAction(Inbox $inbox)
    {
        $this->denyAccessUnlessGranted('view', $inbox);

        // Make the message read, if it's openned by the owner
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('CraftKeenCMSUserBundle:User')
            ->find($this->get('security.token_storage')->getToken()->getUser()->getId());

        if ($user == $inbox->getRecipient()) {
            // Mark message as read
            $message = $em->getRepository('CraftKeenCMSAdminBundle:Inbox')->find($inbox->getId());
            $message->setIsRead(true);
            $em->flush();
        }

        $deleteForm = $this->createDeleteForm($inbox);

        return $this->render('CraftKeenCMSAdminBundle:Inbox:show.html.twig', [
            'inbox' => $inbox,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="craftkeen_cms_admin_inbox_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Inbox $inbox
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Inbox $inbox)
    {
        $this->denyAccessUnlessGranted('edit', $inbox);

        $deleteForm = $this->createDeleteForm($inbox);
        $editForm = $this->createForm('CraftKeen\CMS\AdminBundle\Form\InboxType', $inbox);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('craftkeen_cms_admin_inbox_edit', ['id' => $inbox->getId()]);
        }

        return $this->render('CraftKeenCMSAdminBundle:Inbox:edit.html.twig', [
            'inbox' => $inbox,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Empty Inbox for Current User.
     *
     * @Route("/empty", name="craftkeen_cms_admin_inbox_empty")
     * @Method("DELETE")
     * @param Request $request
     * @return RedirectResponse
     */
    public function emptyAction(Request $request)
    {
        /** @var Form $form */
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('craftkeen_cms_admin_inbox_empty'))
            ->setMethod('DELETE')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $qb->delete(Inbox::class, 'i');
            $qb->where('i.recipient = :user');
            $qb->setParameter('user', $this->getUser());
            $qb->getQuery()->execute();

            $this->addFlash('success', 'Inbox Cleared');
        }
        return $this->redirectToRoute('craftkeen_cms_admin_inbox_index');
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="craftkeen_cms_admin_inbox_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Inbox $inbox
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Inbox $inbox)
    {
        $form = $this->createDeleteForm($inbox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->remove($inbox);
            $em->flush();

            $this->addFlash('success', 'Inbox Message has been removed');
        }

        return $this->redirectToRoute('craftkeen_cms_admin_inbox_index');
    }

    /**
     * @param Inbox $inbox
     * @return Form|FormInterface
     */
    private function createDeleteForm(Inbox $inbox)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('craftkeen_cms_admin_inbox_delete', ['id' => $inbox->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return int
     */
    public function getNewMessagesNumber()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->getDoctrine()
            ->getRepository('CraftKeenCMSAdminBundle:Inbox')
            ->findNew($user);
    }

    /**
     * @return Response
     */
    public function getNewMessagesNumberAction()
    {
        return $this->render('CraftKeenCMSAdminBundle:Inbox:new_messages.html.twig', [
            'new_messages' => $this->getNewMessagesNumber(),
        ]);
    }
}
