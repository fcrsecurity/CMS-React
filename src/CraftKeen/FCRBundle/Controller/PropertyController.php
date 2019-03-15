<?php

namespace CraftKeen\FCRBundle\Controller;

use CraftKeen\CMS\AdminBundle\Entity\Logs;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\FCRBundle\Entity\Manager;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\PropertyGallery;
use CraftKeen\FCRBundle\Entity\PropertyVacancy;
use CraftKeen\FCRBundle\Form\PropertyTranslateType;
use CraftKeen\FCRBundle\Repository\PropertyRepository;
use Doctrine\ORM\EntityManager;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CraftKeen\FCRBundle\Entity\ActiveProvince;
use CraftKeen\FCRBundle\Entity\PropertyDetails;
use CraftKeen\FCRBundle\Form\PropertySearchType;
use CraftKeen\FCRBundle\Entity\PropertySubmission;
use CraftKeen\FCRBundle\Entity\PropertyDemographic;
use CraftKeen\FCRBundle\Entity\PropertyFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends Controller
{
    /**
     * View Press Release Details
     *
     * @Route("/portfolio-leasing/view/{code}", name="craftkeen_fcr_property_view", requirements={"code": ".+"})
     * @ParamConverter("Property", options={"mapping": {"code": "code"}})
     * @Template()
     *
     * @param Request $request
     * @param Property $property
     *
     * @return array|Response
     */
    public function viewAction(Request $request, Property $property)
    {
        if (!$property || $property->getIsHidden() || 'live' != $property->getStatus()) {
            throw $this->createNotFoundException('404. Property was not found!');
        }

        $translatedProperty = $this->get('craft_keen.translation.registry')->translate(
            $property,
            $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()
        );

        $propertySubmission = new PropertySubmission();
        $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertySubmissionType', $propertySubmission);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertySubmission->setProperty($property);
            $propertySubmission->setIpAddress($request->getClientIp());
            $propertySubmission->setCreated();
            $em = $this->getDoctrine()->getManager();
            $em->persist($propertySubmission);
            $em->flush();

            $carbonCopy = [];
            /** @var Manager $manager */
            foreach ($property->getManagers() as $manager) {
                if ($manager->getType() == 'leasing') {
                    $carbonCopy[] = $manager->getEmail();
                }
            }
            $this->sendNotification(
                $propertySubmission->getEmail(),
                $carbonCopy,
                $this->getParameter('fcr_property_cta_email_bcc'),
                $propertySubmission
            );

            $response = [
                'success' => true,
                'message' => $this->get('translator')->trans('Thank you for your submission'),
            ];

            return new JsonResponse($response);
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $copy = $em->getRepository(Property::class)->findOneBy(['copyOf' => $property]);

        return array(
            'property' => $translatedProperty,
            'copy' => $copy,
            'mode' => 'view',
            'form' => $form->createView(),
        );
    }

    /**
     * Search Properties
     *
     * @Route("/portfolio-leasing/search", name="craftkeen_fcr_property_search")
     * @Template()
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return array|Response
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(PropertySearchType::class);
        $form->handleRequest($request);
        $pagination = null;

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Paginator $paginator */
            $paginator = $this->get('knp_paginator');
            $perPage = 30;
            $language = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();

            /** @var PropertyRepository $propertyRepository */
            $propertyRepository = $this->getDoctrine()->getRepository(Property::class);
            $query = $propertyRepository
                ->filterByKeyword(
                    $language,
                    $request->get('keyword')
                );


            $query = $propertyRepository
                ->filterResults(
                    $language,
                    $form->getData()
                );

            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1), /*page number*/
                ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : $perPage
            );
        }

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * Add New Property
     *
     * @Route("portfolio-leasing/wizard/add/{step}/{propertyId}",
     *     name="craftkeen_fcr_property_add_wizard",
     *     requirements={"step": "\d+"}
     * )
     * @Security("has_role('ROLE_LEASING') or has_role('ROLE_LEASING_REGIONAL_COORDINATORS') ")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param int $step
     * @param bool $propertyId
     *
     * @return array|Response
     */
    public function addPropertyWizardAction(Request $request, $step = 1, $propertyId = false)
    {
        switch ($step) {
            // Add General property info
            case 1:
                $site = $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Site::class)
                    ->findOneBy(['id' => 1]); //TODO: replace hard-coded '1'

                $property = new Property();
                $property->setStatus('draft');
                $property->setVersion(1);
                $property->setSite($site);
                $property->setCode($this->generateNewPropertyCode());
                $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertyWizardType', $property);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    // Determine whether the property has Vacant spaces
                    $isVacant = false;
                    $vacancies = $property->getVacancyList();
                    /** @var PropertyVacancy $vacancy */
                    foreach ($vacancies as $vacancy) {
                        if (floatval($vacancy->getVacantSqft())) {
                            $isVacant = true;
                            break;
                        }
                    }
                    $property->setIsVacant($isVacant);
                    $property->setCreatedBy($this->getUser());
                    $property->setUpdatedBy($this->getUser());
                    $em->persist($property);
                    $em->flush();

                    return $this->redirectToRoute('craftkeen_fcr_property_add_wizard', [
                        'step' => 2,
                        'propertyId' => $property->getId()
                    ]);
                }
                return $this->render('CraftKeenFCRBundle:Property:wizard/general.html.twig', [
                    'step' => $step,
                    'propertyId' => $propertyId,
                    'property' => $property,
                    'form' => $form->createView(),
                    'mode' => 'add',
                    'copy' => null
                ]);
                break;
            // Add Details info
            case 2:
                if (!$propertyId) {
                    throw $this->createNotFoundException('404. Wrong Property ID!');
                }
                /** @var Property $property */
                $property = $this->getDoctrine()->getRepository(Property::class)
                    ->findOneById($propertyId);

                if (!$property) {
                    throw $this->createNotFoundException('404. Property was not found');
                }

                // Calculate Total Vacant SQ FT from prev. form.
                $vacantSqft = 0;
                $vacancies = $property->getVacancyList();
                /** @var PropertyVacancy $vacancy */
                foreach ($vacancies as $vacancy) {
                    $vacantSqft += floatval($vacancy->getVacantSqft());
                }

                // Add Property Details
                $propertyDetails = new PropertyDetails();
                $propertyDetails->setProperty($property);
                $propertyDetails->setVacantSqft($vacantSqft);
                $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertyDetailsType', $propertyDetails);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($propertyDetails);
                    $em->flush();

                    return $this->redirectToRoute('craftkeen_fcr_property_add_wizard', [
                        'step' => 3,
                        'propertyId' => $property->getId()
                    ]);
                }

                return $this->render('CraftKeenFCRBundle:Property:wizard/details.html.twig', [
                    'step' => $step,
                    'propertyId' => $propertyId,
                    'propertyDetails' => $propertyDetails,
                    'form' => $form->createView(),
                    'mode' => 'add',
                    'copy' => null,
                    'property' => $property
                ]);
                break;
            case 3:
                if (!$propertyId) {
                    throw $this->createNotFoundException('404. Wrong Property ID!');
                }
                /** @var Property $property */
                $property = $this->getDoctrine()->getRepository(Property::class)
                    ->findOneById($propertyId);

                if (!$property) {
                    throw $this->createNotFoundException('404. Property was not found');
                }
                // Add Property Demographics
                $propertyDemographic = new PropertyDemographic();
                $propertyDemographic->setProperty($property);
                $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertyDemographicType', $propertyDemographic);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($propertyDemographic);
                    $em->flush();

                    return $this->redirectToRoute('craftkeen_fcr_property_add_wizard', [
                        'step' => 4,
                        'propertyId' => $property->getId()
                    ]);
                }

                return $this->render('CraftKeenFCRBundle:Property:wizard/demographic.html.twig', [
                    'step' => $step,
                    'propertyId' => $propertyId,
                    'propertyDemographic' => $propertyDemographic,
                    'form' => $form->createView(),
                    'mode' => 'add',
                    'copy' => null,
                    'property' => $property
                ]);
                break;

            case 4:
                if (!$propertyId) {
                    throw $this->createNotFoundException('404. Wrong Property ID!');
                }
                /** @var Property $property */
                $property = $this->getDoctrine()->getRepository(Property::class)
                    ->findOneById($propertyId);

                if (!$property) {
                    throw $this->createNotFoundException('404. Property was not found');
                }

                // Add Property Filters
                $propertyFilter = new PropertyFilter();
                $propertyFilter->setProperty($property);
                $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertyFilterType', $propertyFilter);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($propertyFilter);
                    $em->flush();

                    return $this->redirectToRoute('craftkeen_fcr_property_edit_wizard', [
                        'id' => $property->getId()
                    ]);
                }

                return $this->render('CraftKeenFCRBundle:Property:wizard/filters.html.twig', [
                    'step' => $step,
                    'propertyId' => $propertyId,
                    'propertyFilter' => $propertyFilter,
                    'form' => $form->createView(),
                    'mode' => 'add',
                    'copy' => null,
                    'property' => $property,
                ]);
                break;
        }

        return [];
    }

    /**
     * Edit Property Via Wizard
     *
     * @Route("portfolio-leasing/wizard/edit/{id}",
     *     name="craftkeen_fcr_property_edit_wizard",
     *     requirements={"id": "\d+"}
     * )
     * @Security("has_role('ROLE_LEASING') or has_role('ROLE_LEASING_REGIONAL_COORDINATORS')")
     * @Method({"GET", "POST"})
     * @ParamConverter("Property", options={"mapping": {"id": "id"}})
     *
     * @param Request $request
     * @param Property $property
     * @return RedirectResponse|Response
     */
    public function editPropertyWizardAction(Request $request, Property $property)
    {
        $formOptions = ['language' => $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()];
        $form = $this->createForm('CraftKeen\FCRBundle\Form\PropertyEditType', $property, $formOptions);
        $form->handleRequest($request);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($property->getStatus() == 'live') {
                $copy = $em->getRepository(Property::class)->findOneBy(['copyOf' => $property]);

                if (null == $copy) {
                    $version = (int)$property->getVersion() + 1;

                    $copy = clone $property;
                    $copy->setCopyOf($property);
                    $copy->setCreatedBy($this->getUser());
                    $copy->setUpdatedBy($this->getUser());
                    $copy->setVersion($version);
                    //$copy->setVersionComment(null);

                    // updated relation for Vacancy List
                    // TODO: think about better solution.
                    foreach ($copy->getVacancyList() as $vacancyListItem) {
                        /** @var PropertyVacancy $cloneVacancyListItem */
                        $cloneVacancyListItem = clone $vacancyListItem;
                        $cloneVacancyListItem->setProperty($copy);
                        $copy->addVacancyList($cloneVacancyListItem);
                    }

                    /** @var PropertyGallery $gallery */
                    foreach ($copy->getGallery() as $gallery) {
                        $gallery->setProperty($copy);
                    }

                    $copy->getDetails()->setProperty($copy);
                    $copy->getDemographic()->setProperty($copy);
                    $copy->getFilters()->setProperty($copy);

                    $em->persist($copy);
                    $em->flush($copy);

                    /** @var PropertyGallery $gallery */
                    foreach ($copy->getGallery() as $gallery) {
                        $em->flush($gallery);
                    }

                    $this->addFlash('success', 'New Draft Created');

                    return $this->redirectToRoute('craftkeen_fcr_property_edit_wizard', ['id' => $copy->getId()]);
                } else {
                    // Update copy of Property
                    $copy->copyDataFrom($property);

                    // Update PropertyDetail in copy
                    $detailsSource = $property->getDetails();
                    /** @var PropertyDetails $details */
                    $details = $copy->getDetails();

                    $details->copyDataFrom($detailsSource);
                    $em->flush($details);

                    // Update PropertyDemographic in copy
                    /** @var PropertyDemographic $demographicSource */
                    $demographicSource = $property->getDemographic();
                    /** @var PropertyDemographic $demographicSource */
                    $demographic = $copy->getDemographic();
                    /** @var PropertyDemographic $demographic */
                    $demographic->copyDataFrom($demographicSource);
                    $em->flush($demographic);

                    // Update PropertyFilters in copy
                    /** @var PropertyFilter $filtersSource */
                    $filtersSource = $property->getFilters();
                    /** @var PropertyFilter $filters */
                    $filters = $copy->getFilters();

                    $filters->copyDataFrom($filtersSource);
                    $em->flush($filters);

                    $em->flush($copy);
                    $this->addFlash('success', 'Draft Updated');

                    return $this->redirectToRoute('craftkeen_fcr_property_edit_wizard', array('id' => $copy->getId()));
                }
            }

            if ($request->get('transition')) {
                $rejectionComment = $request->get('rejectionComment');

                if (null !== $rejectionComment) {
                    $rejectionComment = "\nRejected! Reason: $rejectionComment";
                    $property->setVersionComment($property->getVersionComment() . $rejectionComment);
                }

                $em->flush();

                return $this->redirectToRoute('craftkeen_fcr_property_apply_transition', [
                    'id' => $property->getId(),
                    'transition' => $request->get('transition'),
                ]);
            } else {
                /** @var PropertyVacancy $vacancyListItem */
                foreach ($property->getVacancyList() as $vacancyListItem) {
                    $vacancyListItem->setProperty($property);
                }
                /** @var PropertyGallery $gallery */
                foreach ($property->getGallery() as $gallery) {
                    $gallery->setProperty($property);
                }

                if (!is_null($property->getFilters())) {
                    $property->getFilters()->setProperty($property);
                }

                // In case when property doesn't has Details and or Demographics, we need to create this one.
                if (null == $property->getDetails()->getProperty()) {
                    $property->getDetails()->setProperty($property);
                }
                if (null == $property->getDemographic()->getProperty()) {
                    $property->getDemographic()->setProperty($property);
                }
                $em->flush();
            }

            $this->addFlash(
                'success',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('craftkeen_fcr_property_edit_wizard', array('id' => $property->getId()));
        }

        $draft = $em->getRepository(Property::class)->findOneBy(['copyOf' => $property]);

        $propertyLogs = $propertyDetailsLogs = $propertyDemographicsLogs = [];
        $logRepository = $em->getRepository(Logs::class);
        if (null !== $property) {
            $propertyLogs = $logRepository->getLogEntries($property);
            $propertyDetailsLogs = $logRepository->getLogEntries($property->getDetails());
            $propertyDemographicsLogs = $logRepository->getLogEntries($property->getDemographic());
        }
        return $this->render('CraftKeenFCRBundle:Property:wizard/edit.html.twig', [
            'property' => $property,
            'propertyLogs' => $propertyLogs,
            'propertyDetailsLogs' => $propertyDetailsLogs,
            'propertyDemographicsLogs' => $propertyDemographicsLogs,
            'draft' => $draft,
            'copy' => $draft,
            'form' => $form->createView(),
            'mode' => 'edit',
        ]);
    }

    /**
     * Translate Property Via Wizard
     *
     * @Route("portfolio-leasing/wizard/translate/{id}/{language}",
     *     name="craftkeen_fcr_property_translate_wizard",
     *     requirements={"id": "\d+", "language": "\d+"}
     * )
     * @Security("has_role('ROLE_LEASING')")
     * @Method({"GET", "POST"})
     * @ParamConverter("Property", options={"mapping": {"id": "id"}})
     * @ParamConverter("Language", options={"mapping": {"language": "id"}})
     *
     * @param Request $request
     * @param Property $property
     * @param Language $language
     *
     * @return array|Response
     */
    public function translatePropertyWizardAction(Request $request, Property $property, Language $language)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Property $translatedProperty */
        $existingTranslation = $em->getRepository(Property::class)->findOneBy(['lang' => $language, 'langParent' => $property]);

        // Check if Translation is actually exists, if not - create a new record.
        if (null == $existingTranslation) {
            $translatedProperty = new Property();
            $translatedProperty->copyDataFrom($property);
            if (null !== $property->getDetails()) {
                $details = new PropertyDetails();
                $translatedProperty->setDetails($details->copyDataFrom($property->getDetails()));
            }
            if (null !== $property->getDemographic()) {
                $demographics = new PropertyDemographic();
                $translatedProperty->setDemographic($demographics->copyDataFrom($property->getDemographic()));
            }
            if (null !== $property->getFilters()) {
                $filter = new PropertyFilter();
                $translatedProperty->setFilters($filter->copyDataFrom($property->getFilters()));
            }
            $translatedProperty->setCreated(new \DateTime());
            $translatedProperty->setCreatedBy($this->getUser());
            $translatedProperty->setUpdated(null);
            $translatedProperty->setUpdatedBy(null);
            $translatedProperty->setStatus('draft');
            $translatedProperty->setLang($language);
            $translatedProperty->setLangParent($property);
        } else {
            return $this->redirectToRoute('craftkeen_fcr_property_edit_wizard', array(
                'id' => $existingTranslation->getId()
            ));
        }

        $form = $this->createForm(PropertyTranslateType::class, $translatedProperty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PropertyDetails $details */
            $details = $form->getData()->getDetails();
            $details->setProperty($translatedProperty);
            $translatedProperty->setDetails($details);
            $em->persist($translatedProperty);
            $em->flush();

            $this->addFlash(
                'success',
                'Translation saved!'
            );

            return $this->redirectToRoute('craftkeen_fcr_property_edit_wizard', array(
                'id' => $translatedProperty->getId()
            ));
        }

        return $this->render('CraftKeenFCRBundle:Property:wizard/translate.html.twig', [
            'property' => $translatedProperty,
            'form' => $form->createView(),
            'mode' => 'edit',
            'copy' => null
        ]);
    }

    /**
     * Generate Map Data JSON
     *
     * @Route("/portfolio-leasing/map-data/{query}",
     *     name="craftkeen_fcr_property_mapdata",
     *     requirements={"query": ".+"}
     * )
     *
     * @param Request $request
     * @param string $query
     *
     * @return array|Response
     */
    public function mapDataAction(Request $request, $query)
    {
        $response = [
            'message' => 'Wrong request',
            'success' => false
        ];

        switch ($query) {
            case 'active_provinces':
                $response = [
                    'message' => 'Active Provinces fetched',
                    'success' => true,
                    'data' => $this->getDoctrine()->getRepository(ActiveProvince::class)
                        ->findProvincesMap(
                            $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()
                        )
                ];
                break;

            case 'properties':
                $response = [
                    'message' => 'Active Properties fetched',
                    'success' => true,
                    'data' => $this->getDoctrine()->getRepository(Property::class)
                        ->findPropertiesMap(
                            $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()
                        )
                ];
                break;

            case 'cities':
                $response = [
                    'message' => 'Active Cities fetched',
                    'success' => true,
                    'data' => $this->getDoctrine()->getRepository(Property::class)
                        ->findCitiesMap(
                            $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()
                        )
                ];
                break;

            case 'getProperties':
                $response = [
                    'message' => 'Getting Properties Listing',
                    'success' => true,
                    'data' => $this->getDoctrine()->getRepository(Property::class)
                        ->findPropertiesListing(
                            $this->get('craft_keen.translation.provider.language')->getCurrentLanguage(),
                            $request->query->get('province'),
                            $request->query->get('offset'),
                            $request->query->get('limit'),
                            $request->query->get('filter'),
                            $request->query->get('nolimit'),
                            $request->query->get('js')
                        )
                ];
                break;
        }

        return new JsonResponse($response);
    }

    /**
     *
     * @param string $to
     * @param array $cc
     * @param array $bcc
     * @param PropertySubmission $propertySubmission
     */
    protected function sendNotification($to, $cc, $bcc, PropertySubmission $propertySubmission)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Thank you for your submission')
            ->setFrom(
                $this->getParameter('fcr_property_cta_email_from'),
                $this->getParameter('fcr_property_cta_email_label')
            )
            ->setTo($to)
            ->setReplyTo($to)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setBody(
                $this->get('templating')->render('email/fcr_property_cta_email.html.twig', [
                    'property' => $propertySubmission->getProperty(),
                    'submission' => $propertySubmission,
                ]),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

    /**
     * Apply transition for Property
     *
     * @Route("/property-apply-transition/{id}/{transition}", name="craftkeen_fcr_property_apply_transition")
     * @Method("GET")
     *
     * @param Request $request
     * @param Property $object
     *
     * @return RedirectResponse
     */
    public function applyTransitionAction(Request $request, Property $object)
    {
        $transition = $request->get('transition');
        $id = $object->getId();

        try {
            if ($transition == 'publish') {
                $copy = $object->getCopyOf();
                if (!is_null($copy)) {
                    $id = $copy->getId();
                }
            }

            if ($transition == 'reject') {
                $rejectionComment = $request->get('rejectionComment');
                if (null !== $rejectionComment) {
                    $rejectionComment = "\nRejected! Reason: $rejectionComment";
                    $object->setVersionComment($object->getVersionComment() . $rejectionComment);
                }
            }

            $this->get('workflow.property_publishing')->apply($object, $transition);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('craftkeen_fcr_property_edit_wizard', ['id' => $id])
        );
    }

    /**
     * Deletes a Property entity.
     *
     * @Route("/portfolio-leasing/wizard/delete/{id}", name="craftkeen_fcr_property_delete")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Property $object */
        $object = $em->getRepository(Property::class)->find($id);

        /** @var FormInterface $form */
        $form = $this->createDeleteForm($object);
        $form->handleRequest($request);

        $liveProperty = null;

        $em = $this->getDoctrine()->getManager();
        $status = $object->getStatus();

        if ('live' == $status) {
            // Remove also translations
            $translations = $em->getRepository(Property::class)->findByLangParent($id);
            /** @var Property $translation */
            foreach ($translations as $translation) {
                $em->remove($translation);
            }
            $em->remove($object);
            $em->flush();
        }

        $draft = $em->getRepository(get_class($object))->findBy(
            ['copyOf' => $id]
        );
        if ('live' !== $status || count($draft) > 0) {
            // Actually remove element. everything but live element should be permanently removed from the system
            ### Start - Prepare Event Listener to avoid soft-deletable filter
            ### for draft records, as we need really delete them.
            // initiate an array for the removed listeners
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

            // TODO: Figure out why we have such a nonsense here, This conditions will never happen, as we are already filtered by this conditions
            if ('live' == $status) {
                // Deletes all draft for Live property permanently
                foreach ($draft as $item) {
                    $em->remove($item);
                }
            } else {
                /** @var Property $liveProperty */
                $liveProperty = null;
                if (null !== $object->getCopyOf()) {
                    $liveProperty = $em->getRepository(get_class($object))->find($object->getCopyOf());
                }
                $em->remove($object);
            }

            $em->flush();

            // re-add the removed listener back to the event-manager
            foreach ($originalEventListeners as $eventName => $listener) {
                $em->getEventManager()->addEventListener($eventName, $listener);
            }
            ### Stop
        }

        if (null !== $liveProperty) {
            $this->addFlash('success', 'Draft deleted');
            return $this->redirectToRoute('craftkeen_fcr_property_edit_wizard', ['id' => $liveProperty->getId()]);
        }
        $this->addFlash('success', 'Property deleted');
        return $this->redirectToRoute('craftkeen_fcr_admin_leasing_property_index');
    }

    /**
     * createDeleteForm
     *
     * @param mixed $id
     *
     * @return FormInterface
     */
    protected function createDeleteForm($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Property $object */
        $object = $em->getRepository(Property::class)->find($id);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('craftkeen_fcr_property_delete', ['id' => $object->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Recursive PropertyCode Generation
     *
     * @param int $length
     *
     * @return string
     */
    private function generateNewPropertyCode($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $code = '';

        for ($i = 0; $i < $length / 2; $i++) {
            $code .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        for ($i = 0; $i < $length / 2; $i++) {
            $code .= $digits[mt_rand(0, strlen($digits) - 1)];
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Property $object */
        $duplicate = $em->getRepository(Property::class)->findByCode($code);
        if (null == $duplicate) {
            return $code;
        } else {
            // Try another code
            $this->generateNewPropertyCode($length);
        }
    }
}
