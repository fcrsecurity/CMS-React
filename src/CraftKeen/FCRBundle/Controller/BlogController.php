<?php

namespace CraftKeen\FCRBundle\Controller;

use CraftKeen\FCRBundle\Entity\RetailArt;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * View Blog Article Details
     *
     * @Route("/retail-life/{slug}", name="craftkeen_fcr_retail-art_view", requirements={"slug": ".+"})
     * @ParamConverter("RetailArt", options={"mapping": {"slug": "slug"}})
     * @Template()
     *
     * @param RetailArt $post
     *
     * @return array|Response
     * @internal param string $code
     *
     */
    public function viewAction(RetailArt $post)
    {
        if (!$post) {
            throw $this->createNotFoundException('404. Post was not found!');
        }

        $translatedPost = $this->get('craft_keen.translation.registry')->translate(
            $post,
            $this->get('craft_keen.translation.provider.language')->getCurrentLanguage()
        );

        $related = $this->getDoctrine()->getRepository(RetailArt::class)->getRelated($translatedPost);
        $copy = $this->getDoctrine()->getRepository(RetailArt::class)->findOneByCopyOf($post);

        return array(
            'post' => $translatedPost,
            'copy' => $copy,
            'mode' => 'view',
            'related' => $related
        );
    }
}
