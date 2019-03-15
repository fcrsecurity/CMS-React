<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 20.11.17
 * Time: 10:48
 */

namespace CraftKeen\BrochureBuilderBundle\Controller;

use CraftKeen\BrochureBuilderBundle\Service\FileManagerService;
use FM\ElfinderBundle\Controller\ElFinderController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * @Route("/brochure/filemanager")
 *
 * Class FileManagerController
 * @package CraftKeen\BrochureBuilderBundle\Controller\FileManager
 */
class FileManagerController extends ElFinderController
{

    /**
     * @Route("/", name="filemanager_front")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {
        $request->setLocale(substr($request->getLocale(), 0, 2));
        $propertyId = $request->query->get('property');
        return parent::showAction($request, FileManagerService::ELFINDER_INSTANCE, $propertyId);
    }

    /**
     * @Route("/new", name="filemanager_front_new")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexNewAction(Request $request) {
        $request->setLocale(substr($request->getLocale(), 0, 2));
        return parent::showAction($request, self::ELFINDER_INSTANCE, '');
    }

    /**
     * @Route("/api/", name="filemanager_back")
     *
     * @param Request $request
     */
    public function apiAction(Request $request) {
        $service = $this->get("file_manager.service");
        $propertyId = $request->query->get('property');
        $connector = $service->getConnector($propertyId, $this->getUser());
        $connector->run($request->query->all());
    }

    /**
     * @Route("/proxy/{url}", name="filemanager_proxy", requirements={"url"=".+"})
     *
     * @param Request $request
     */
    public function proxyAction(Request $request, $url) {
        $url = @urldecode($url);
        $name = @tempnam(@sys_get_temp_dir(), 'proxy_');
        if ($name) {
            $file = @file_get_contents($url);
            if ($file && @file_put_contents($name, $file) > 0) {
                return new BinaryFileResponse($name);
            }
        }

        throw $this->createNotFoundException();
    }

    /**
     * @Route("/content/{drive}/{filename}", name="filemanager_content", requirements={"filename"=".+"})
     *
     * @param Request $request
     * @param $drive
     * @param $filename
     * @return BinaryFileResponse
     */
    public function contentAction(Request $request, $drive, $filename) {
        $efParameters = $this->getParameter('fm_elfinder');
        $roots = $efParameters['instances'][FileManagerService::ELFINDER_INSTANCE]['connector']['roots'];
        if (isset($roots[$drive])) {
            $path = $roots[$drive]['path'];
            if (is_file($path . DIRECTORY_SEPARATOR . $filename)) {
                return new BinaryFileResponse($path . DIRECTORY_SEPARATOR . $filename);
            }
        }

        throw $this->createNotFoundException();

    }

}
