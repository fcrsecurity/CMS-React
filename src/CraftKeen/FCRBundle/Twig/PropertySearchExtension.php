<?php

namespace CraftKeen\FCRBundle\Twig;

use CraftKeen\FCRBundle\Form\PropertySearchType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

class PropertySearchExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('property_search_form', [$this, 'getPropertySearchForm']),
        ];
    }

    /**
     * @param Request $request
     *
     * @return FormView
     */
    public function getPropertySearchForm(Request $request = null)
    {
        return $this->getFormView(PropertySearchType::class, $request);
    }

    /**
     * @param string $formClass
     * @param Request $request
     *
     * @return FormView
     */
    protected function getFormView($formClass, Request $request = null)
    {
        $form = $this->container->get('form.factory')->create($formClass);
        $form->handleRequest($this->getRequest($request));

        return $form->createView();
    }

    /**
     * @param Request $request
     *
     * @return null|Request
     */
    protected function getRequest(Request $request = null)
    {
        return is_null($request) ? $this->container->get('request_stack')->getMasterRequest() : $request;
    }

}
