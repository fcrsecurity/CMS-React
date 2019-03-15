<?php

namespace CraftKeen\FCRBundle\Controller;

use CraftKeen\FCRBundle\Entity\AodaLongDescription;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Aodalongdescription controller.
 *
 */
class AodaLongDescriptionController extends Controller
{
    /**
     * Redirect to url from field LongDescription
     *
     * @Route("aoda-long-description/{class}/{field}/{id}", name="admin_fcr_aoda_long_description_redirect")
     * @param $class
     * @param $id
     * @param $field
     *
     * @return Response
     * @throws \LogicException
     */
    public function redirectToLongDescriptionUrl($class, $id, $field)
    {
        $data = $this->getDoctrine()->getRepository(AodaLongDescription::class)->findOneBy([
            'objectClass' => $class,
            'objectId' => $id,
            'fieldName' => $field
        ]);

        if ($data) {
            if ($data->getType() == 'text') {
                $response =  new Response($data->getLongDescription(), 200);
            }
            else {
                $response =  new Response($data->getLongDescription(), 200);
                $response->headers->set('Content-Type', 'text/html');
            }
        }
        else {
            $response = new Response('Description not found', 200);
        }


        return $response;
    }
}
