<?php

namespace CraftKeen\BrochureBuilderBundle\Controller;

use CraftKeen\BrochureBuilderBundle\Entity\Brochure;
use CraftKeen\BrochureBuilderBundle\Service\BrochureBuilder;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\Office;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @Route("/brochure")
 * @Security("has_role('ROLE_BROCHURE_APPROVER') or has_role('ROLE_BROCHURE_EDITOR')")
 *
 * Class BrochureController
 * @package CraftKeen\BrochureBuilderBundle\Controller\Brochure
 */

class BrochureController extends Controller
{
    /**
     * @return BrochureBuilder;
     */
    private function service() {
        return $this->get('brochure_builder.service');
    }

    /**
     * @return Workflow
     */
    private function workflow() {
       return $this->get('state_machine.brochure_publishing');
    }

    /**
     * @Route("/", name="brochure_dashboard_list")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request) {
        $brochures = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()
                ->getRepository(Brochure::class)
                ->findNotDeleted(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($request->query->get('per_page')) ? (int)$request->query->get('per_page') : 10 /*limit per page*/
        );

        return $this->render('BrochureBuilderBundle:Dashboard:list.html.twig', [
            'pagination' => $brochures
        ]);
    }

    /**
     * @Route("/delete/{id}", name="brochure_delete")
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Security("has_role('ROLE_BROCHURE_EDITOR')")
     * @Method({"GET"})
     *
     * @param Brochure $brochure
     * @return RedirectResponse
     */
    public function deleteAction(Brochure $brochure) {
        $workflow = $this->workflow();
        if ($workflow->can($brochure, Brochure::TRANSITION_DELETE)) {
            $workflow->apply($brochure, Brochure::TRANSITION_DELETE);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('info', $this->get('translator')->trans('Brochure deleted successfully'));
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('Cannot delete brochure'));
        }
        return $this->redirectToRoute('brochure_dashboard_list');
    }

    /**
     * @Route("/pdf/{id}", name="brochure_download_pdf")
     * @Method({"GET"})
     *
     * @param int $id
     * @return PdfResponse
     */
    public function pdfAction($id)
    {
        // get brochure
        $webPath = $this->getParameter('web_path');
        $brochure = $this->getDoctrine()
            ->getRepository(Brochure::class)
            ->find($id);

        return new PdfResponse(
            file_get_contents($webPath.$brochure->getPdf()),
            'brochure_'.$id.'.pdf'
        );
    }

    /**
     * @Route("/approve/{id}", name="brochure_approve", requirements={"id": "\d+"})
     * @Method({"GET"})
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Security("has_role('ROLE_BROCHURE_APPROVER')")
     *
     * @param Brochure $brochure
     * @return Response
     */
    public function approveAction(Brochure $brochure)
    {
        $workflow = $this->workflow();
        $property = $brochure->getProperty();

        if ($workflow->can($brochure, Brochure::TRANSITION_PUBLISH) && $property && !is_null($property->getDetails())) {
            $pdfPath = '/files/brochure/pdf/';
            $webPath = $this->getParameter('web_path');
            $fileName = $this->service()->generatePdfName($brochure);
            if(!is_dir($webPath.$pdfPath)) {
                @mkdir($webPath.$pdfPath, 0777, true);
            }
            $pathToUploadDir = $this->getParameter('ckcms_library_path'). DIRECTORY_SEPARATOR. 'properties'.DIRECTORY_SEPARATOR.'marketing-pdfs';
            $urlToUploadDir = $this->getParameter('ckcms_library_url'). DIRECTORY_SEPARATOR. 'properties'.DIRECTORY_SEPARATOR.'marketing-pdfs';

            if(!is_dir($pathToUploadDir)) {
                @mkdir($pathToUploadDir, 0777, true);
            }
            if(!is_dir($urlToUploadDir)) {
                @mkdir($urlToUploadDir, 0777, true);
            }

            // generate pdf file
            $pdf = $this->service()->generatePdfFromHtml(
                $this->service()->generateHtmlFromBrochure($brochure)
            );
            if ($pdf) {
                $em = $this->getDoctrine()->getManager();
                $parent = $this->getDoctrine()->getRepository(Brochure::class)->getBrochureForProperty($brochure->getProperty());
                if (null !== $parent && $workflow->can($parent, Brochure::TRANSITION_ARCHIVE)) {
                    $workflow->apply($parent, Brochure::TRANSITION_ARCHIVE);
                    $brochure->setLangParent($parent);
                    $brochure->setVersion(intval($parent->getVersion()) + 1);
                }
                $workflow->apply($brochure, Brochure::TRANSITION_PUBLISH);
                file_put_contents($webPath.$pdfPath . $fileName, $pdf);
                $brochure->setPdf($pdfPath . $fileName);
                $em->persist($brochure);
                $em->flush();

                symlink($webPath.$pdfPath . $fileName, $pathToUploadDir.DIRECTORY_SEPARATOR.$fileName);

                $property->getDetails()->setMarketingPdf($urlToUploadDir.DIRECTORY_SEPARATOR.$fileName);
                @chmod($pathToUploadDir.DIRECTORY_SEPARATOR.$fileName, 0444);
                $em->persist($property);
                $em->flush();

            } else {
                $this->addFlash('danger', $this->get('translator')->trans('Cannot generate pdf'));
            }
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('Cannot approve brochure'));
        }
        return $this->redirectToRoute('brochure_dashboard_list');
    }

    /**
     * @Route("/{id}", name="brochure_edit", requirements={"id": "\d+"})
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Security("has_role('ROLE_BROCHURE_EDITOR')")
     * @Method({"GET"})
     *
     * @param Brochure $brochure
     * @return Response
     */
    public function editBrochureAction(Brochure $brochure)
    {
        if (Brochure::STATUS_DRAFT !== $brochure->getStatus()) {
            $this->addFlash('danger', $this->get('translator')->trans('Cannot edit brochure not in a draft'));
            return $this->redirectToRoute('brochure_dashboard_list');
        }

        $offices = $this->service()->getOffices($brochure);
        return $this->render('BrochureBuilderBundle:BrochureBuilder:edit.html.twig', [
            'data' => json_encode($brochure->toJson()),
            'offices' => json_encode(array_map(function($office) {
                return [
                    'id' => $office->getId(),
                    'name' => $office->getCity(),
                    'header' => $office->getHeader(),
                    'line1' => $office->getAddress(),
                    'line2' => $office->getCity().', '.$office->getProvince().', '.$office->getPostal(),
                ];
            }, $offices)),
            'id' => $brochure->getId()
        ]);
    }

    /**
     * @Route("/{id}", name="brochure_edit_save", requirements={"id": "\d+"}, defaults={"sendForApproval": false})
     * @Route("/{id}/approve", name="brochure_edit_save_approve", requirements={"id": "\d+"}, defaults={"sendForApproval": true})
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Brochure $brochure
     * @param bool $sendForApproval
     * @return Response
     */
    public function saveBrochureAction(Request $request, Brochure $brochure, $sendForApproval)
    {
        if ($this->getUser() !== $brochure->getCreatedBy() && !$this->getUser()->hasRole(User::ROLE_BROCHURE_EDITOR)) {
            return new JsonResponse([
                'result' => false
            ], 403);
        }

        if ($request->isXmlHttpRequest() && Brochure::STATUS_DRAFT == $brochure->getStatus()) {
            $content = @json_decode($request->getContent(), true);
            if (is_array($content)) {

                $brochure->populateByAjaxContent($content);

                $errors = [];
                if ($this->service()->validateAndSave($brochure, $errors, null, false, $sendForApproval)) {
                    return new JsonResponse([
                        'result' => true
                    ]);
                } else {
                    return new JsonResponse([
                        'result' => false,
                        'errors' => $errors
                    ], 422);
                }
            }
        }

        return new JsonResponse([
            'result' => false
        ], 400);
    }

    /**
     * @Route("/{id}/pdf", name="brochure_view_pdf", requirements={"id": "\d+"})
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewBrochurePdfAction(Brochure $brochure)
    {
        if (Brochure::STATUS_LIVE == $brochure->getStatus()) {
            $path = $this->getParameter('web_path') . $brochure->getPdf();
            $pathArray = explode('/', $path);
            $data = file_get_contents($path);
            $name = end($pathArray);
        } else {
            $data = $this->service()->generatePdfFromHtml($this->service()->generateHtmlFromBrochure($brochure));
            $name = $this->service()->generatePdfName($brochure);
        }

        return new PdfResponse(
            $data,
            $name,
            'application/pdf',
            'inline'
        );
    }

    /**
     * Show view pdf action
     *
     * @Route("/{id}/view", name="brochure_view", requirements={"id": "\d+"})
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Method({"GET"})
     *
     * @param Brochure $brochure
     * @return Response
     */
    public function viewAction(Brochure $brochure)
    {
        return $this->render('BrochureBuilderBundle:Dashboard:view.html.twig', [
            'brochure'    => $brochure,
        ]);
    }

    /**
     * @Route("/{id}/pdf/download", name="brochure_download_pdf", requirements={"id": "\d+"})
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadBrochurePdfAction(Brochure $brochure)
    {
        $path = $this->getParameter('web_path') . $brochure->getPdf();
        $pathArray = explode('/', $path);
        return new PdfResponse(
            file_get_contents($path),
            end($pathArray)
        );
    }

    /**
     * @Route("/{id}/pdf.html", name="brochure_view_pdf_as_html", requirements={"id": "\d+"})
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewBrochureAsHtmlAction(Brochure $brochure)
    {
        return new Response(
            $this->service()->generateHtmlFromBrochure($brochure)
        );
    }

    /**
     * @Route("/create/{id}", name="brochure_create_from_property", requirements={"id": "\d+"})
     * @ParamConverter("Property", options={"mapping": {"id": "id"}})
     * @Method({"GET"})
     * @Security("has_role('ROLE_BROCHURE_EDITOR')")
     *
     * @param Property $property
     * @return Response
     */
    public function createFromPropertyAction(Property $property)
    {
        $brochure = null;
        if ($property->getDetails()) {
            $brochure = $this->service()->createFromProperty($property, $this->getUser());
        }
        if ($brochure) {
            return $this->redirectToRoute('brochure_edit', ['id' => $brochure->getId()]);
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('Cannot create brochure'));
            return $this->redirectToRoute('brochure_dashboard_list');
        }
    }

    /**
     * @Route("/manager", name="brochure_dashboard_manager")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fileManagerAction(Request $request) {
        return $this->render('BrochureBuilderBundle:Dashboard:manager.html.twig');
    }

    /**
     * Apply transition for Brochure
     *
     * @Route("/brochure-apply-transition/{id}/{transition}", name="craftkeen_brochurebuilder_brochure_apply_transition")
     * @Method("GET")
     *
     * @param Request $request
     * @param Brochure $object
     *
     * @return RedirectResponse
     */
    public function applyTransitionAction(Request $request, Brochure $object)
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

            $this->workflow()->apply($object, $transition);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('brochure_dashboard_list', ['id' => $id])
        );
    }

}
