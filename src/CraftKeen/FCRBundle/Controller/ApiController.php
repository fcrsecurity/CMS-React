<?php

namespace CraftKeen\FCRBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\Widget;

/** Api Controller
 *
 * @Route("api/")
 */
class ApiController extends Controller
{
    /**
     * @Route("get-page-layout", name="api_get_page_layout")
     * @Method("GET")
     * @param Request $request
     *
     * @return Response
     * @throws \LogicException
     */
    public function getPageLayout(Request $request)
    {
        $id = $request->query->get('id');
        if (!$this->checkKey($request->headers->get('key')))
        {
            $response = new Response('{"Error":"Forbidden"}', 403);
            return $response;
        }

        $data = $this->getDoctrine()->getRepository(Page::class)->find($id);
        if ($data) {
            $layout = $data->getLayout();
            $response = new Response($layout, 200);
        }
        else {
            $response = new Response('{"Error":"Page Not Found"}', 404);
        }


        return $response;
    }

    /**
     * @Route("get-widget-data", name="api_get_widget_data")
     * @Method("GET")
     * @param Request $request
     *
     * @return Response
     * @throws \LogicException
     */
    public function getWidgetData(Request $request)
    {
        $id = $request->query->get('id');
        if (!$this->checkKey($request->headers->get('key')))
        {
            $response = new Response('{"Error":"Forbidden"}', 403);
            return $response;
        }

        $data = $this->getDoctrine()->getRepository(Widget::class)->find($id);

        if ($data) {
            $widgetType = $data->getType();
            $widgetDefaultData = $data->getDefaultData();

            $result = '{"widgetType":"' . $widgetType . '",';
            $result .= '"widgetParams":' . $widgetDefaultData .'}';

            $response = new Response($result, 200);
        }
        else {
            $response = new Response('{"Error":"Widget Not Found"}', 404);
        }
        return $response;
    }

    private function checkKey($key)
    {
        $originalKey = $this->getParameter('api_key');
        if ($originalKey == $key) {
            return true;
        }
        else {
            return false;
        }
    }
}
