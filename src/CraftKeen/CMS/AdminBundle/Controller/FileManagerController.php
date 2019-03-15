<?php

namespace CraftKeen\CMS\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileManagerController extends Controller
{
    /**
     * @Route("/file-manager", name="craftkeen_cms_admin_file-manager_index")
     */
    public function indexAction()
    {
        return $this->render('CraftKeenCMSAdminBundle:FileManager:index.html.twig');
    }
}
