<?php

namespace CraftKeen\FCRBundle\Controller\Admin;

use CraftKeen\FCRBundle\Entity\AodaLongDescription;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use CraftKeen\CMS\AdminBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Aodalongdescription controller.
 *
 * @Route("admin/aoda-long-description")
 */
class AodaLongDescriptionController extends BaseCrudController
{
    /**
     * Lists all AodaLongDescriptionController entities.
     *
     * @Route("/", name="admin_fcr_aoda_long_description_index")
     * @Security("has_role('ROLE_USER')")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param array $filters
     * @return array
     */
    public function indexAction(Request $request, $filters = [])
    {
        return parent::indexAction($request, $filters = []);
    }

    /**
     * Creates a new AodaLongDescriptionController entity.
     *
     * @Route("/new", name="admin_fcr_aoda_long_description_new")
     * @Security("has_role('ROLE_CONTRIBUTOR') or has_role('ROLE_ADMINISTRATOR')")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param array $formOptions
     *
     * @return array|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function newAction(Request $request, $formOptions = [])
    {
        $object = $this->getNewEntity();
        $error = null;

        $form = $this->createForm($this->getEntityFormType(), $object, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository($object->getObjectClass())->find($object->getObjectId());
            if ($data) {
                $object->setAccess($data->getAccess());

                $em->persist($object);
                $em->flush();
                $this->addFlash('success', 'New Long Description Added');

                return $this->redirectToRoute($this->getEntityBaseRoute() . 'show', ['id' => $object->getId()]);
            }
            else {
                $error = "Object of class " . $object->getObjectClass() .
                    " with id = " . $object->getObjectId() . " not found";
            }
        }

        return [
            'object' => $object,
            'form' => $form->createView(),
            'error' =>$error,
        ];
    }

    /**
     * Finds and displays a AodaLongDescriptionController entity.
     *
     * @Route("/{id}", name="admin_fcr_aoda_long_description_show")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("GET")
     * @Template()
     * @param int $id
     * @return array
     */
    public function showAction($id)
    {
        return parent::showAction($id);
    }

    /**
     * Displays a form to edit an existing AodaLongDescriptionController entity.
     *
     * @Route("/{id}/edit", name="admin_fcr_aoda_long_description_edit")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param int $id
     * @param array $formOptions
     *
     * @return array|RedirectResponse
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $id, $formOptions = [])
    {
        $object = $this->findRecord($id);

        $deleteForm = $this->createDeleteForm($object);
        $editForm = $this->createForm($this->getEntityFormType(), $object, $formOptions);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_fcr_aoda_long_description_edit', array('id' => $id));
        }

        return [
            'object' => $object,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a AodaLongDescriptionController entity.
     *
     * @Route("/{id}", name="admin_fcr_aoda_long_description_delete")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     * @Method("DELETE")
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function deleteAction(Request $request, $id)
    {
        $aodaLongDescription = $this->getRepository()->findOneBy(['id' => $id]);

        $form = $this->createDeleteForm($aodaLongDescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($aodaLongDescription);
            $em->flush();
        }

        return $this->redirectToRoute('admin_fcr_aoda_long_description_index');
    }

    /**
     * @see BaseApiController::getRepository()
     * @return AodaLongDescription|ObjectRepository
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository(AodaLongDescription::class);
    }

    /**
     * @see BaseApiController::getNewEntity()
     * @return AodaLongDescription
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getNewEntity() {
        // Load Site Manager
        $siteManager = $this->get('craft_keen_cms.site_manager');
        /** @var Site $site */
        $currentSite = $siteManager->getCurrentSite();

        $site = $this->getDoctrine()
            ->getManager()
            ->getRepository(Site::class)
            ->findOneBy(['id' => $currentSite->getId()]);

        $object = new AodaLongDescription();
        $object->setSite($site);
        $object->setLang($this->get('craft_keen.translation.provider.language')->getCurrentLanguage());

        return $object;
    }

    /**
     * @see BaseApiController::getEntityFormType()
     * @return String
     */
    public function getEntityFormType() {
        return 'CraftKeen\FCRBundle\Form\AodaLongDescriptionType';
    }
}
