<?php

namespace CraftKeen\FCRBundle\Controller;

use CraftKeen\FCRBundle\Entity\PressRelease;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/fcr")
     * @return Response
     */
    public function indexAction()
    {
        //Get PressRelease
        $pressRelease = new PressRelease();
        $translatedPressRelease = $this->get('craft_keen.translation.registry')->translate(
            $pressRelease,
            $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()
        );
        dump($translatedPressRelease);

        die("dieBreak at File: ".__FILE__." line: ".__LINE__);
        return $this->render('CraftKeenFCRBundle:Default:index.html.twig', ['release' => $translatedPressRelease]);
    }
}
