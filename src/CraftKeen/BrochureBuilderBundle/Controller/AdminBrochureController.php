<?php

namespace CraftKeen\BrochureBuilderBundle\Controller;

use CraftKeen\BrochureBuilderBundle\Entity\Brochure;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\BrochureBuilderBundle\Form\UserType;
use CraftKeen\BrochureBuilderBundle\Form\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use CraftKeen\BrochureBuilderBundle\Repository\BrochureRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use CraftKeen\FCRBundle\Controller\Admin\LeasingPermissionsTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Workflow\Workflow;
use CraftKeen\BrochureBuilderBundle\Service\BrochureBuilder;

/**
 * Brochure controller.
 *
 * @Route("brochure/brochure")
 * @Security("has_role('ROLE_BROCHURE_APPROVER')")
 */
class AdminBrochureController extends BaseCrudController
{
    use LeasingPermissionsTrait;

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
     * Lists all brochure entities.
     *
     * @Route("/", name="brochure_admin_brochure_index")
     * @Method("GET")
     * @param Request $request
     * @param array $filters
     * @Template("BrochureBuilderBundle:Admin:Brochure/index.html.twig")
     * @return array
     */
    public function indexAction(Request $request, $filters = []) {
        return parent::indexAction($request, $filters);
    }

    /**
     * Finds and displays a Brochure entity.
     *
     * @Route("/{id}", name="brochure_admin_brochure_show")
     * @Method("GET")
     * @Template("BrochureBuilderBundle:Admin:Brochure/show.html.twig")
     * @param int $id
     * @return array|RedirectResponse
     */
    public function showAction($id) {
//        $html = $this->renderView('BrochureBuilderBundle:pdf:base.html.twig',[]);
//        $options = [
//            'no-outline' => true,
//            'page-size' => 'Letter',
//            'margin-bottom'	=> 0,
//            'margin-left'	=> 0,
//            'margin-right'	=> 0,
//            'margin-top'	=> 0
//        ];
//        return new PdfResponse(
//            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, $options),
//            'file.pdf',
//            'application/pdf',
//            'inline'
//        );
        return parent::showAction($id);
    }


    /**
     * Displays a form to edit an existing Brochure entity.
     *
     * @Route("/{id}/edit", name="brochure_admin_brochure_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_BROCHURE_EDITOR')")
     * @Template("BrochureBuilderBundle:Admin:Brochure/edit.html.twig")
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, $id, $formOptions = []) {
        return parent::editAction($request, $id, $formOptions);
    }

    /**
     * @Route("/approve/{id}", name="brochure_admin_brochure_approve")
     * @Method({"GET"})
     * @param Request $request
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveAction(Request $request, $id)
    {
        $workflow = $this->workflow();
        // get brochure
        $brochure = $this->getDoctrine()
            ->getRepository(Brochure::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();

        if ($workflow->can($brochure, Brochure::TRANSITION_PUBLISH)) {
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

            // generate pdf file
            $pdf = $this->service()->generatePdfFromHtml(
                $this->service()->generateHtmlFromBrochure($brochure)
            );

            if ($pdf) {
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
                $property = $brochure->getProperty();
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

        return $this->redirectToRoute('brochure_admin_brochure_index');
    }

    /**
     * @Route("/reject/{id}", name="brochure_admin_brochure_reject")
     * @Method({"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rejectAction(Request $request, $id)
    {
//        return $this->applyTransitionAction($request, $id);
        $brochure = $this->getDoctrine()
            ->getRepository(Brochure::class)
            ->find($id);

        if ($request->getMethod() == 'POST') {
            $comment = $request->request->get('comment');
            $brochure->setRejectComment($comment);
        }
        $em = $this->getDoctrine()->getManager();

        if ($this->get('state_machine.brochure_publishing')->can($brochure,'reject')) {
            $this->get('state_machine.brochure_publishing')->apply($brochure, 'reject');
        }
        $em->persist($brochure);
        $em->flush($brochure);
        return $this->redirectToRoute('brochure_admin_brochure_index');
    }

    /**
     * @Route("/apply-transition/{id}/{transition}", name="brochure_admin_brochure_apply_transition")
     * @Method("GET")
     * @param Request $request
     * @param int $id
     *
     * @return array|RedirectResponse
     */
    public function applyTransitionAction(Request $request, $id)
    {
        /** @var Brochure $brochure */
        $brochure = $this->getEntity($id);
        if (false === $brochure) {
            return $this->createNotFoundException();
        }
        $transition = $request->get('transition');
        try {
            $this->get('workflow.brochure_publishing')->apply($brochure, $transition);

            if ($transition == 'publish') {
                $sourceBrochure = $brochure->getCopyOf();

                if (is_null($sourceBrochure)) {
                    $this->get('doctrine')->getManager()->flush();
                } else {
                    // Copy edited data to source brochure
                    $sourceBrochure = $this->getDoctrine()->getRepository(Brochure::class)->findOneBy(['id' => $sourceBrochure]);
                    $sourceBrochure->setData($brochure->getData());
                    $sourceBrochure->setStatus('live');

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sourceBrochure);
                    $em->flush($sourceBrochure);

                    // Delete copied brochure
                    $em->remove($brochure);
                    $em->flush($brochure);

                    $id = $sourceBrochure->getId();
                }
            } else {
                if ($transition == 'to_review') {

                }
                $this->get('doctrine')->getManager()->flush();
            }

        } catch (ExceptionInterface $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirectToRoute('brochure_admin_brochure_index'); // TODO: change path to show
    }


    /**
     * Deletes a Brochure entity.
     *
     * @Route("/{id}", name="brochure_admin_brochure_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_BROCHURE_EDITOR')")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        $brochure = $this->getEntity($id);
        $workflow = $this->workflow();
        if ($workflow->can($brochure, Brochure::TRANSITION_DELETE)) {
            $workflow->apply($brochure, Brochure::TRANSITION_DELETE);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('info', $this->get('translator')->trans('Brochure deleted successfully'));
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('Cannot delete brochure'));
        }
        return $this->redirectToRoute('brochure_admin_brochure_index');
    }

    /**
     * Finds and Revert a Brochure entity to a specific version
     *
     * @Route("/{id}/revert/{version}", name="brochure_admin_brochure_revert")
     * @Security("has_role('ROLE_BROCHURE_ADMINISTRATOR')")
     * @Method("GET")
     * @param int $id
     * @param $version
     * @return RedirectResponse
     */
    public function revertAction($id, $version)
    {
        return parent::revertAction($id, $version);
    }

    /**
     * @Route("/rollback/{id}", name="brochure_admin_brochure_rollback")
     * @ParamConverter("Brochure", options={"mapping": {"id": "id"}})
     * @Security("has_role('ROLE_BROCHURE_ADMINISTRATOR')")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Brochure $brochure
     * @return RedirectResponse
     */
    public function rollbackAction(Request $request, Brochure $brochure) {
        $workflow = $this->workflow();

        $parent = $brochure->getLangParent();
        if (null !== $parent) {
            if (   $workflow->can($parent, Brochure::TRANSITION_REVERT)
                && $workflow->can($brochure, Brochure::TRANSITION_RETRACT)) {
                $workflow->apply($parent, Brochure::TRANSITION_REVERT);
                $workflow->apply($brochure, Brochure::TRANSITION_RETRACT);
                $comment = $request->request->get('comment');
                $brochure->setRevertComment($comment);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('info', $this->get('translator')->trans('Brochure reverted successfully'));
            } else {
                $this->addFlash('danger', $this->get('translator')->trans('Cannot revert brochure'));
            }
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('Brochure has no version to revert'));
        }
        return $this->redirectToRoute('brochure_admin_brochure_index');
    }

    /**
     * @see BaseApiController::getRepository()
     * @return BrochureRepository|ObjectRepository
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(Brochure::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return Brochure
     */
    public function getNewEntity() {
        return $this->setEntityDefaults( new Brochure() );
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\TenantType';
    }
}
