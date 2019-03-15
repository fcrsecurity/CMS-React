<?php

namespace CraftKeen\FCRBundle\Controller;

use CraftKeen\CMS\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CraftKeen\FCRBundle\Entity\CareersPosition;
use CraftKeen\FCRBundle\Entity\CareerPositionSubmission;
use CraftKeen\CMS\UserBundle\Entity\User;

class CareersController extends Controller
{
    /**
     * View Press Release Details
     *
     * @Route("/careers/jobs/{code}", name="craftkeen_fcr_careers_view", requirements={"code": ".+"})
     * @ParamConverter("CareersPosition", options={"mapping": {"code": "code"}})
     * @Template()
     *
     * @param Request $request
     * @param CareersPosition $position
     * @return array
     */
    public function viewAction(Request $request, CareersPosition $position) {
        if (!$position ) {
            throw $this->createNotFoundException('404. Position was not found!');
        }
        
        $careerPositionSubmission = new Careerpositionsubmission();
        $form = $this->createForm('CraftKeen\FCRBundle\Form\CareerPositionSubmissionType', $careerPositionSubmission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Submited form contains errors');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $careerPositionSubmission->setPosition( '['.$position->getCode().'] '.$position->getTitle().', '.$position->getCity() );

            $em = $this->getDoctrine()->getManager();
                                   
            /** @var UploadedFile $file */
            $file = $careerPositionSubmission->getResume();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('fcr_careers_resume_path'), $fileName
            );
            $careerPositionSubmission->setResume($fileName);
            $em->persist($careerPositionSubmission);
            $em->flush();
            
            // Send To CatsOne
            $connector = $this->get('craft_keen_fcr.service.catsone_connector');
            $connector->setApiKey($this->getParameter('fcr_catsone_api_key'));
            
            if ($connector->applyForAJob( $careerPositionSubmission , $this->getParameter('fcr_careers_resume_path')) ) {

                $cc = [];
                /** @var UserRepository $repository */
                $repository = $this->getDoctrine()->getRepository(User::class);
                $users = $repository->findUserByRole('ROLE_HR');
                foreach ($users as $user) {
                    $cc[$user->getEmail()] =  $user->getUserName();
                }

                if (count($cc) == 0) {
                    $cc[$this->getParameter('ckcms_form_to_email')] =  "FCR";
                }

                $this->sendNotification(
                        $careerPositionSubmission->getEmail(), $cc,
                        $this->getParameter('fcr_property_cta_email_bcc'),
                        $careerPositionSubmission);
                        
                        $this->addFlash('success', 'Thank you for your submission.');
            } else {
                $this->addFlash('warning', 'We saved your submissions but there is technical difficulties with Careers server, please try again later.');
            }
        }

        return array(
            'position' => $position,
            'form' => $form->createView(),
        );
    }

    /**
     * SendNotification
     *
     * @param $to
     * @param $cc
     * @param $bcc
     * @param CareerPositionSubmission $careerPositionSubmission
     */
    protected function sendNotification($to, $cc, $bcc, CareerPositionSubmission $careerPositionSubmission) {
        $message = \Swift_Message::newInstance()
                ->setSubject('Thank you for your submission')
                ->setFrom($this->getParameter('fcr_property_cta_email_from'), $this->getParameter('fcr_property_cta_email_label'))
                ->setTo($to)
                ->setReplyTo($to)
                ->setCc($cc)
                ->setBcc($bcc)
                ->setBody(
                $this->get('templating')->render('email/fcr_resume_email.html.twig', [
                    'careerPositionSubmission' => $careerPositionSubmission,
                ]), 'text/html'
        );
        $this->get('mailer')->send($message);
    }
}
