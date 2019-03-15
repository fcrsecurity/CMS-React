<?php

namespace CraftKeen\Bundle\SearchBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("search/", name="search_index")
     *
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm('CraftKeen\Bundle\SearchBundle\Form\SearchType', [], [
            'action' => $this->generateUrl('search_index'),
        ]);

        $form->handleRequest($request);

        $searchResult['matches'] = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $searchResult = $this->get('craft_keen.search.provider')->search($form->get('search_input')->getData());
        }

        return [
            'searchResult'  => $searchResult,
            'form'          => $form->createView()
        ];
    }
}
