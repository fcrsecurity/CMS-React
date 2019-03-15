<?php

namespace CraftKeen\BrochureBuilderBundle\Service;

use Doctrine\ORM\EntityManager;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\Office;
use CraftKeen\BrochureBuilderBundle\Entity\Brochure;
use CraftKeen\BrochureBuilderBundle\Entity\BrochureAerial;
use CraftKeen\BrochureBuilderBundle\Entity\BrochureContact;
use CraftKeen\BrochureBuilderBundle\Entity\BrochureCover;
use CraftKeen\BrochureBuilderBundle\Entity\BrochurePlan;
use CraftKeen\BrochureBuilderBundle\FileManager\FileSystemDriver;
use Symfony\Component\Validator\Constraints\Valid;
use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use CraftKeen\BrochureBuilderBundle\Controller\BrochureController;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Knp\Snappy\Pdf;
use Symfony\Bundle\TwigBundle\TwigEngine;

class BrochureBuilder
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * @var Workflow
     */
    protected $workflow;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Pdf
     */
    protected $snappy;

    /**
     * @var TwigEngine
     */
    protected $templating;

    /**
     * @var FileManagerService
     */
    protected $fileManagerService;

    /**
     * Constructor
     * @param ContainerInterface $container
     * @param Router $router
     * @param EntityManager $em
     * @param RecursiveValidator $validator
     * @param Workflow $workflow
     * @param Session $session
     * @param Pdf $snappy
     * @param TwigEngine $templating
     * @param FileManagerService $fileManagerService
     */
    public function __construct(
        ContainerInterface $container,
        Router $router,
        EntityManager $em,
        RecursiveValidator $validator,
        Workflow $workflow,
        Session $session,
        Pdf $snappy,
        TwigEngine $templating,
        FileManagerService $fileManagerService
    )
    {
        $this->container = $container;
        $this->router = $router;
        $this->em = $em;
        $this->validator = $validator;
        $this->workflow = $workflow;
        $this->session = $session;
        $this->snappy = $snappy;
        $this->templating = $templating;
        $this->fileManagerService = $fileManagerService;
    }

    /**
     * @param Property $property
     * @param User $user
     * @param array &$errors = []
     *
     * @return Brochure|false
     */
    public function createFromProperty(Property $property, User $user, &$errors = [])
    {
        // try to find existing brochure for property
        $parent = $this->em->getRepository(Brochure::class)->getBrochureForProperty($property);

        $brochure = new Brochure();
        $brochure->populateByProperty($property, $parent);
        $brochure->setCreatedBy($user);
        $brochure->setUpdatedBy($user);

        $offices = array_filter($this->getOffices($brochure), function(Office $office) {
            return $office->getIsMain();
        });

        $office = array_shift($offices);
        if ($office) {
            $brochure->populateByOffice($office);
        }

        $sitePlans = $this->fileManagerService->getSitePlans($property->getId(), $user);
        foreach ($sitePlans as $sitePlanImage) {
            $brochurePlan = new BrochurePlan();
            $url = $this->router->generate(
                'filemanager_content',
                ['drive' => 'properties', 'filename' => $property->getId() . '/' . FileSystemDriver::SITE_PLANS_DIRECTORY . '/' . basename($sitePlanImage)],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            
            $brochurePlan->setImage($url);
            $brochure->addPlan($brochurePlan);
        }

        // proxify hero image
        $heroImage = $brochure->getHeroImage();
        $heroImageUrl = $heroImage ? $heroImage->getImage() : '';
        $urlStartWith = $this->container->getParameter('ckcms_library_url');

        if ($heroImageUrl && strpos($heroImageUrl, $urlStartWith) !== 0) {
            $heroImage->setImage(
                $this->router->generate(
                    'filemanager_proxy',
                    ['url' => urlencode($heroImageUrl)],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            );
        };

        return $this->validateAndSave($brochure, $errors, null, true);
    }

    /**
     * @param Brochure $brochure
     * @param array &$errors = []
     * @param array $groups
     * @param boolean $isInitial = false
     *
     * @return Brochure|false
     */
    public function validateAndSave(Brochure $brochure, &$errors = [], $groups = null, $isInitial = false, $sendForApproval = false)
    {
        $violations = $this->validator->validate($brochure);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return false;
        }

        if (!$isInitial && $sendForApproval && $this->workflow->can($brochure,Brochure::TRANSITION_REVIEW)) {
            $this->em->persist($brochure);
            $this->em->flush();
            if ($this->workflow->can($brochure,Brochure::TRANSITION_REVIEW)) {
                $this->workflow->apply($brochure, Brochure::TRANSITION_REVIEW);
            }elseif ($this->workflow->can($brochure,Brochure::TRANSITION_RETRACT)) {
                $this->workflow->apply($brochure, Brochure::TRANSITION_RETRACT);
            }
        }
        $this->em->persist($brochure);
        $this->em->flush();

        return $brochure;
    }

    /**
     * @param Brochure $brochure
     * @return string
     */
    public function generateHtmlFromBrochure(Brochure $brochure)
    {
        return $this->templating->render('BrochureBuilderBundle:pdf:view.html.twig',[
            'brochure' => $brochure
        ]);
    }

    /**
     * @warning This function closes user session
     *
     * @param string $html
     * @return string
     */
    public function generatePdfFromHtml($html)
    {
        // $cookie = [];
        // $cookie[$this->session->getName()] = $this->session->getId();
        // $this->snappy->setOption('cookie', $cookie);
        // $this->session->save();
        // session_write_close();
        // requires for internal images

        $options = [
            'no-outline' => true,
            'page-size' => 'Letter',
            'margin-bottom'	=> 0,
            'margin-left'	=> 0,
            'margin-right'	=> 0,
            'margin-top'	=> 0
        ];
        
        return $this->snappy->getOutputFromHtml($html, $options);
    }

    /**
     * @return array
     */
    public function getOffices(Brochure $brochure)
    {
        return $this->em->getRepository(Office::class)->findBy(
            [
                'lang' => $brochure->getLang()->getId(),
                'status' => 'live'
            ],
            [
                'sortOrder' => 'ASC',
            ]
        );
    }

    /**
     * Create name for pdf file
     *
     * @param Brochure $brochure
     * @return string
     */
    public function generatePdfName(Brochure $brochure) {
        return preg_replace('/[\s]+/s', '_',
            implode(' ', [
                trim($brochure->getName()),
                'PropertyBrochure',
                $brochure->getId().'.pdf'
            ])
        );
    }
}
