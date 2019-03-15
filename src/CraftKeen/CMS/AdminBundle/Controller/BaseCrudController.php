<?php

namespace CraftKeen\CMS\AdminBundle\Controller;

use CraftKeen\FCRBundle\Entity\BaseEntity;
use CraftKeen\FCRBundle\Repository\TranslatableEntityRepository;
use Doctrine\Common\EventManager;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\NoResultException;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Logs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Each entity controller must extends this class.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
abstract class BaseCrudController extends Controller
{
    /**
     * This method should return the entity's repository.
     *
     * @return EntityRepository|TranslatableEntityRepository
     */
    abstract public function getRepository();

    /**
     * This method should return a new entity instance to be used for the "create" action.
     *
     * @return Object
     */
    abstract public function getNewEntity();
    
    /**
     * This method should return a new entity form Path
     *
     * @return String
     */
    abstract public function getEntityFormType();

    /**
     * This method returns a base string of the route.
     *
     * @return String
     */
    public function getEntityBaseRoute()
    {
        return $this->getNewEntity()->getEntityBaseRoute();
    }

    /**
     * Returns an entity from its ID, or FALSE in case of error.
     *
     * @param integer $id
     *
     * @return Object|boolean
     */
    protected function getEntity($id)
    {
        try {
            return $this->getRepository()->find($id);
        } catch (NoResultException $ex) {
            return false;
        }
    }

    /**
     * Base "list" action.
     *
     * @param Request $request
     * @param array $filters
     *
     * @return array
     */
    protected function indexAction(Request $request, $filters = [])
    {
        $currentLanguage = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();
        $findBy = [
            'lang' => $currentLanguage,
            'copyOf' => null,
        ];

        $findBy['lang'] = $currentLanguage;
        if ($request->query->get('filterBy') && is_array($request->query->get('filterBy'))) {
            foreach ($request->query->get('filterBy') as $key => $filter) {
                if (strlen($filter) > 0) {
                    $findBy[$key] = $filter;
                }
            }
            $paginationResults = $this->getRepository()
                ->findBy($findBy, ['sortOrder' => 'ASC', 'id' => 'DESC']);
        } else {
            $paginationResults = $this->getRepository()
                ->findBy(['lang' => $currentLanguage], ['sortOrder' => 'ASC', 'id' => 'DESC']);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $paginationResults,
            $request->query->getInt('page', 1), /* page number */
            (int)$request->query->get('per_page') ?: 10/* limit per page */
        );

        return [
            'pagination' => $pagination,
            'filterBy' => $filters,
        ];
    }

    /**
     * Base "Create" action.
     *
     * @param Request $request
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     */
    protected function newAction(Request $request, $formOptions = [])
    {
        $object = $this->getNewEntity();
        if (method_exists($object, 'setLang')) {
            $object->setLang($this->get('craft_keen.translation.provider.language')->getCurrentLanguage());
        }
        $form = $this->createForm($this->getEntityFormType(), $object, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object->setStatus('draft');
            $object->setCreatedBy($this->getUser());
            $object->setCreated(new \DateTime());
            $object->setUpdated(new \DateTime());
            $object->setVersionComment('Initial Content');
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->addFlash('success', 'New Item Added');

            return $this->redirectToRoute($this->getEntityBaseRoute() . 'show', ['id' => $object->getId()]);
        }

        return [
            'object' => $object,
            'form' => $form->createView(),
        ];
    }

    /**
     * Base "Show" Action
     *
     * @param int $id
     *
     * @return array
     */
    protected function showAction($id)
    {
        $object = $this->findRecord($id);
        $deleteForm = $this->createDeleteForm($id);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Logs::class);
        $logs = $repo->getLogEntries($object);

        return [
            'logs' => $logs,
            'object' => $object,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Base "Revert" Action
     *
     * @param int $id
     *
     * @param $version
     *
     * @return RedirectResponse
     *
     * @throws NotFoundHttpException
     * @throws \Gedmo\Exception\UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    protected function revertAction($id, $version)
    {
        $object = $this->findRecord($id);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Logs::class);
        $repo->revert($object, $version);
        $em->persist($object);
        $em->flush();
        $this->addFlash('success', 'Content Successfully Reverted to Version: ' . $version);

        return $this->redirectToRoute($this->getEntityBaseRoute() . 'show', ['id' => $object->getId()]);
    }

    /**
     * Base Editing Action
     *
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     */
    protected function editAction(Request $request, $id, $formOptions = [])
    {
        $object = $this->findRecord($id);

        $deleteForm = $this->createDeleteForm($object);
        $editForm = $this->createForm($this->getEntityFormType(), $object, $formOptions);
        $editForm->handleRequest($request);

        /* Add Draft Detection */
        $draft = $this->getRepository()->findOneBy(['copyOf' => $object]);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($object->getStatus() == 'live') {
                $copy = $this->getRepository()->findOneBy(['copyOf' => $object]);

                if (null == $copy) {
                    $version = (int)$object->getVersion() + 1;

                    $copy = clone $object;
                    $copy->setStatus('draft');
                    $copy->setCopyOf($object);
                    $copy->setCreatedBy($this->getUser());
                    $copy->setUpdatedBy($this->getUser());
                    $copy->setCreated(new \DateTime());
                    $copy->setVersion($version);

                    $em->persist($copy);
                    $em->flush($copy);
                    $this->addFlash('success', 'New Draft Created');

                    return $this->redirectToRoute($this->getEntityBaseRoute() . 'edit', ['id' => $copy->getId()]);
                } else {
                    $copyBuf = $copy;
                    $copy = $object;
                    $copy->setId($copyBuf->getId());
                    $copy->setCreatedBy($copyBuf->getCreatedBy());
                    $copy->setCopyOf($copyBuf->getCopyOf());
                    $copy->setStatus($copyBuf->getStatus());
                    $copy->setUpdated(new \DateTime());

                    $em->detach($object);
                    $em->persist($copy);
                    $em->flush($copy);
                    $this->addFlash('success', 'Draft Updated');

                    return $this->redirectToRoute($this->getEntityBaseRoute() . 'edit', ['id' => $copy->getId()]);
                }
            } else {
                $this->getDoctrine()->getManager()->flush();
            }

            if ($request->get('transition')) {
                return $this->redirectToRoute($this->getEntityBaseRoute() . 'apply_transition', [
                    'id' => $object->getId(),
                    'transition' => $request->get('transition'),
                ]);
            } else {
                $this->addFlash('success', 'Record updated');

                return $this->redirectToRoute($this->getEntityBaseRoute() . 'edit', ['id' => $object->getId()]);
            }
        }

        return [
            'object' => $object,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'draft' => $draft,
        ];
    }

    /**
     * Base Translate Action
     *
     * @param Request $request
     * @param $langParent
     * @param Language $translateToLanguage
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     */
    protected function translateAction(
        Request $request,
        $langParent,
        Language $translateToLanguage,
        $formOptions = []
    ) {
        $langParent = $this->findRecord($langParent);
        // Get Existing translation
        $translation = $this->getRepository()->findExistingTranslation($langParent, $translateToLanguage);

        if (null !== $translation) {
            // Edit the same Object
            return $this->redirectToRoute($this->getEntityBaseRoute() . 'edit', ['id' => $translation->getId()]);
        } else {
            $adding = true;
            // New Translation needs to be added
            $translation = clone $langParent;
            $translation->setLang($translateToLanguage);
            $translation->setLangParent($langParent);
            $translation->setStatus('draft');
            $form = $this->createForm($this->getEntityFormType(), $translation, $formOptions);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($adding) {
                $em->persist($translation);
                $this->addFlash('success', 'Translation created');
            } else {
                $this->addFlash('success', 'Translation updated');
            }
            $em->flush();

            return $this->redirectToRoute($this->getEntityBaseRoute() . 'translate_to', [
                'id' => $langParent->getId(),
                'translateToLanguage' => $translateToLanguage->getId(),
            ]);
        }

        return [
            'langParent' => $langParent,
            'object' => $translation,
            'form' => $form->createView(),
        ];
    }

    /**
     * Base "Translate Index" Action
     *
     * @param Request $request
     * @param int $id
     *
     * @return array
     */
    protected function translateIndexAction(Request $request, $id)
    {
        $object = $this->findRecord($id);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getRepository()->findTranslations($object),
            $request->query->getInt('page', 1), /* page number */
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10/* limit per page */
        );

        return [
            'langParent' => $object,
            'pagination' => $pagination,
            'languages' => $this->get('craft_keen.translation.provider.language')->getLanguages(),
        ];
    }

    /**
     * Base "Apply Transition Action" Action
     *
     * @param Request $request
     * @param int $id
     *
     * @return array|RedirectResponse
     */
    protected function applyTransitionAction(Request $request, $id)
    {
        $object = $this->findRecord($id);
        $id = $object->getId();

        $transition = $request->get('transition');

        try {
            switch ($transition) {
                case 'publish':
                    /** @var object $copy */
                    $copy = $object->getCopyOf();
                    if (!is_null($copy)) {
                        $id = $copy->getId();
                    }
                    break;
                case 'reject':
                    // Apply Rejected Comment
                    $rejectionComment = $request->get('rejectionComment');
                    if (null !== $rejectionComment) {
                        $rejectionComment = "\nRejected! Reason: $rejectionComment";
                        $object->setVersionComment($object->getVersionComment() . $rejectionComment);
                    }
                    break;
            }
            $this->get('workflow.temporary_publishing')->apply($object, $transition);

            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl($this->getEntityBaseRoute() . 'show', ['id' => $id])
        );
    }

    /**
     * Base "Delete" Action
     *
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     * @throws NotFoundHttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \UnexpectedValueException
     */
    protected function deleteAction(Request $request, $id)
    {
        $object = $this->findRecord($id);
        /** @var FormInterface $form */
        $form = $this->createDeleteForm($object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ('live' == $object->getStatus()) {
                $em->remove($object);
                $em->flush();
            }

            $this->processDraftsOnDelete($object);
        }
        $this->addFlash('success', 'Item deleted');

        return $this->redirectToRoute($this->getEntityBaseRoute() . 'index');
    }

    /**
     * Base "Create Delete Form" Action
     *
     * @param mixed $id
     *
     * @return FormInterface
     */
    protected function createDeleteForm($id)
    {
        $object = $this->findRecord($id);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl($this->getEntityBaseRoute() . 'delete', ['id' => $object->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Base "Find Record by ID"
     *
     * @param int $id
     *
     * @return Object|BaseEntity
     *
     * @throws NotFoundHttpException
     */
    protected function findRecord($id)
    {
        $object = $this->getEntity($id);
        if (null === $object) {
            throw $this->createNotFoundException('Record does not exists');
        }

        return $object;
    }

    /**
     * @param BaseEntity|Object $object
     */
    protected function processDraftsOnDelete(BaseEntity $object)
    {
        $class = ClassUtils::getClass($object);
        $em = $this->get('doctrine')->getManagerForClass($class);
        $status = $object->getStatus();
        $draft = $em->getRepository($class)->findBy(['copyOf' => $object->getId()]);
        /** @var EventManager $eventManager */
        $eventManager = $em->getEventManager();

        if ('live' !== $status || count($draft) > 0) {
            // Actually remove element. everything but live element should be permanently removed from the system
            ### Start - Prepare Event Listener to avoid soft-deletable filter
            ### for draft records, as we need really delete them.
            // initiate an array for the removed listeners
            $originalEventListeners = $this->removeSoftDeleteableListeners($eventManager);

            if ('live' == $status) {
                foreach ($draft as $item) {
                    $em->remove($item);
                }
            } else {
                $em->remove($object);
            }

            $em->flush();
            $this->restoreSoftDeleteableListeners($eventManager, $originalEventListeners);
            // re-add the removed listener back to the event-manager
            foreach ($originalEventListeners as $eventName => $listeners) {
                foreach ($listeners as $listener) {
                    $eventManager->addEventListener($eventName, $listener);
                }
            }
            ### Stop
        }
    }

    /**
     * @param EventManager $eventManager
     *
     * @return array
     */
    protected function removeSoftDeleteableListeners(EventManager $eventManager)
    {
        $originalEventListeners = [];
        // cycle through all registered event listeners
        foreach ($eventManager->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof SoftDeleteableListener) {
                    // store the event listener, that gets removed
                    $originalEventListeners[$eventName][] = $listener;
                    // remove the SoftDeletableSubscriber event listener
                    $eventManager->removeEventListener($eventName, $listener);
                }
            }
        }

        return $originalEventListeners;
    }

    /**
     * @param EventManager $eventManager
     * @param array $originalEventListeners
     */
    protected function restoreSoftDeleteableListeners(EventManager $eventManager, array $originalEventListeners = [])
    {
        // re-add the removed listener back to the event-manager
        foreach ($originalEventListeners as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                $eventManager->addEventListener($eventName, $listener);
            }
        }
    }
}
