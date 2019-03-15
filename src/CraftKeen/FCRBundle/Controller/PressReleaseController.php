<?php

namespace CraftKeen\FCRBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CraftKeen\FCRBundle\Entity\PressRelease;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\Route as PageRoute;
use Symfony\Component\HttpFoundation\Response;

class PressReleaseController extends Controller
{
    /**
     * View Press Release Details
     *
     * @Route("/investors/press-release/{slug}",
     *     name="craftkeen_fcr_press_release_view",
     *     requirements={"slug": "(?!editMode)(.+)"}
     * )
     * @ParamConverter("pressRelease", options={"mapping": {"slug": "slug"}})
     * @Template()
     *
     * @param PressRelease $pressRelease
     *
     * @return array|Response
     * @internal param type $slug
     */
    public function viewAction(PressRelease $pressRelease)
    {
        if ($pressRelease->getIsHidden()) {
            throw $this->createNotFoundException('404. Page not found!');
        }

        $currentLang = $this->get('craft_keen.translation.provider.language')->getCurrentLanguage();

        if ($currentLang != $pressRelease->getLang()) {
            if (is_null($pressRelease->getLangParent())) {
                $pressRelease = $this->getDoctrine()->getRepository(PressRelease::class)
                    ->findOneByLangParent($pressRelease);
            } else {
                $pressRelease = $pressRelease->getLangParent();
            }
        }

        $related = $this->getDoctrine()->getRepository(PressRelease::class)->getRelated($pressRelease);

        $landingPageSlug = "";
        $landingPage = $this->getDoctrine()->getRepository(Page::class)->findByName('Press Releases');
        $routeObj = $this->getDoctrine()->getRepository(PageRoute::class)->findOneBy(
            ['page' => $landingPage],
            ['id' => 'ASC']
        );

        if (null !== $routeObj) {
            $landingPageSlug = $routeObj->getSlug();
        }

        $copy = $this->getDoctrine()->getRepository(PressRelease::class)->findOneByCopyOf($pressRelease);

        return array(
            'article' => $pressRelease,
            'copy' => $copy,
            'mode' => 'view',
            'related' => $related,
            'landingPageSlug' => $landingPageSlug
        );
    }
}
