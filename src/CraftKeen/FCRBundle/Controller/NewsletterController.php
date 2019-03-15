<?php

namespace CraftKeen\FCRBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use CraftKeen\FCRBundle\Entity\Newsletter;
use DrewM\MailChimp\MailChimp;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

/**
 * @Route("/newsletter")
 */
class NewsletterController extends Controller
{
    /**
     * Download CSV
     *
     * @Route("/newsletter-download-csv", name="craftkeen_fcr_newsletter_download_csv_index")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return StreamedResponse|Response
     */
    public function downloadCSV(Request $request)
    {
        $MailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
        $data = $MailChimp->get('lists');

        $list = [];
        foreach ($data['lists'] as $item) {
            $list[$item['name']] = $item['id'];
        }

        $form = $this->createForm('CraftKeen\FCRBundle\Form\NewsletterCsvType', $list);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $maxItems = 50; // Maximum MailChimp returns 50 items
            $idList = $form->getData()['list'];
            $data = $MailChimp->get('lists/' . $idList . '/members?offset=0&count=' . $maxItems);
            $emails = $data['members'];
            $totalItems = $data['total_items'];
            $position = $maxItems;

            while ($position < $totalItems) {
                $data = $MailChimp->get('lists/' . $idList .
                    '/members?offset=' . $position . '&count=' . $maxItems);
                foreach ( $data['members'] as $member) {
                    $emails[] = $member;
                }
                $position += $maxItems;
            }

            $response = new StreamedResponse();
            $response->setCallback(
                function () use ($emails) {
                    $handle = fopen('php://output', 'r+');
                    foreach ($emails as $item) {
                        $buf = array($item['email_address'], $item['last_changed']);
                        fputcsv($handle, $buf);
                    }
                    fclose($handle);
                }
            );

            // Find List name
            $listName = 'general-list';
            foreach ($list as $name => $id) {
                if ( $idList  == $id) {
                    $listName = $name;
                    break;
                }
            }

            $filename = 'newsletter_submissions_export_'.$listName.'_'.date('Y-m-d_H-i-s',time()).'.csv';
            $response->headers->set('Content-Type', 'application/force-download');
            $response->headers->set('Content-Disposition','attachment; filename="'.$filename.'"');

            return $response;
        }

        return $this->render('CraftKeenFCRBundle:Newsletter:newsletterCSV.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Subscribe for Newsletters.
     *
     * @Route("/subscribe", name="craftkeen_fcr_newsletter_subscribe")
     * @Method("POST")
     * @Template()
     * @param Request $request
     * @return array|JsonResponse
     */
    public function subscribeAction(Request $request)
    {
        $newsletter = new Newsletter();
        $form = $this->createForm('CraftKeen\FCRBundle\Form\NewsletterType', $newsletter, [
            'action' => $this->generateUrl('craftkeen_fcr_newsletter_subscribe'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsletter->setEnabled(true);
            $newsletter->setCreated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletter);
            $em->flush();

            $this->saveToChimpList($newsletter);

            return new JsonResponse([
                'success' => true,
                'message' => 'Thank you for your submission',
            ]);
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Subscribe for Newsletters.
     *
     * @Route("/subscribe-careers", name="craftkeen_fcr_newsletter_subscribe_careers")
     * @Method("POST")
     * @Template()
     * @param Request $request
     * @return array|JsonResponse
     */
    public function subscribeCareersAction(Request $request)
    {
        $newsletter = new Newsletter();
        $form = $this->createForm('CraftKeen\FCRBundle\Form\NewsletterType', $newsletter, [
            'action' => $this->generateUrl('craftkeen_fcr_newsletter_subscribe_careers'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsletter->setEnabled(true);
            $newsletter->setCreated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletter);
            $em->flush();

            $this->saveToChimpList($newsletter);

            return new JsonResponse([
                'success' => true,
                'message' => 'Thank you for your submission',
            ]);
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
    * Subscribe for Newsletters.
    *
    * @Route("/unsubscribe", name="craftkeen_fcr_newsletter_unsubscribe")
    * @Method("GET")
    * @param Request $request
    * @return array|JsonResponse
    */
    public function unsubscribeAction(Request $request)
    {
        $token = new CsrfToken('unsubscribeForm', $request->query->get("_csrf_token"));
        /** @var CsrfTokenManager $csrf */
        $csrf = $this->get('security.csrf.token_manager');

        $email = $request->query->get('email');
        if ($email && $csrf->isTokenValid($token)) {
            $lists = [];
            if ( is_array($request->query->get('list')) ) {
                $lists = $request->query->get('list');
            }
            $this->removeFromChimpListAction($email, $lists);
            return new JsonResponse([
                'success' => true,
                'message' => 'You un-subscribed successfully',
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Wrong Request',
        ]);
    }

    /**
     * Save Subscription to MailChimp List
     *
     * @param Newsletter $submission
     * @return array|false
     */
    protected function saveToChimpList(Newsletter $submission)
    {
        $result = [
            'success' => false,
            'message' => 'Unknown Request',
        ];
        $MailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));

        // Save to General List for submission without selection
        if (!$submission->getNews() && !$submission->getLeasing() && !$submission->getCareers()) {
            $list_id = $this->getParameter('mailchimp_list_general'); //'121d8833e3';
            $result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $submission->getEmail(),
                'status' => 'subscribed',
            ]);
        }

        if ($submission->getNews()) {
            $list_id = $this->getParameter('mailchimp_list_news'); //'8f84620db2';
            $result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $submission->getEmail(),
                'status' => 'subscribed',
            ]);
        }

        if ($submission->getLeasing()) {
            $list_id = $this->getParameter('mailchimp_list_leasing'); //'8641bac7b0';
            $result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $submission->getEmail(),
                'status' => 'subscribed',
            ]);
        }

        if ($submission->getCareers()) {
            $list_id = $this->getParameter('mailchimp_list_careers'); //'4540b1acec';
            $result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $submission->getEmail(),
                'status' => 'subscribed',
            ]);
        }

        return $result;
    }

    /**
     * Removing user from the mailChimp list.
     *
     * @param $email
     * @param array $listIds
     */
    protected function removeFromChimpListAction($email, $listIds = array())
    {
        $MailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));

        if (empty($listIds)) {
            $lists = $MailChimp->get('lists');
            foreach ($lists['lists'] as $key => $list) {
                $listId = $list['id'];

                $subscriberHash = $MailChimp->subscriberHash($email);
                $MailChimp->delete("lists/$listId/members/$subscriberHash");
            }
        } else {
            foreach ($listIds as $key => $listId) {
                $subscriberHash = $MailChimp->subscriberHash($email);
                $MailChimp->delete("lists/$listId/members/$subscriberHash");
            }
        }
    }
}
