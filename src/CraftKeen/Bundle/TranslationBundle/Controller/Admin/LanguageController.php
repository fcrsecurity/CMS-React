<?php

namespace CraftKeen\Bundle\TranslationBundle\Controller\Admin;

use CraftKeen\Bundle\TranslationBundle\Form\Type\LanguageType;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends Controller
{

    /**
     * @Route("language", name="craftkeen_translation_admin_language_index")
     *
     * @Template()
     */
    public function indexAction()
    {
        return [
            'entities' => $this->getDoctrine()->getRepository(Language::class)->findAll(),
        ];
    }

    /**
     * @Route("language/add", name="craftkeen_translation_admin_language_add")
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        return $this->updateHandler(new Language(), $request);
    }

    /**
     * @param Language $language
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    protected function updateHandler(Language $language, Request $request)
    {
        $form = $this->createForm(LanguageType::class, $language);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManagerForClass(Language::class);
                $data = $form->getData();
                $em->persist($data);
                $em->flush();

                return $this->redirectToRoute('craftkeen_translation_admin_language_index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
