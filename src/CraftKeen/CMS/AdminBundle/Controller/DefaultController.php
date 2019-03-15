<?php

namespace CraftKeen\CMS\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="craftkeen_cms_admin_index")
     */
    public function indexAction()
    {
        return $this->render('CraftKeenCMSAdminBundle:Default:index.html.twig');
    }
}
