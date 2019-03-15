<?php

namespace CraftKeen\Bundle\TranslationBundle\EventListener\Kernel;

use CraftKeen\Bundle\TranslationBundle\Exception\LanguageNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\Routing\RouterInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    /** @var ContainerInterface */
    protected $container;

    /** @var RouterInterface */
    protected $router;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            // must be registered after authentication
            KernelEvents::REQUEST => [['onKernelRequest', 7]],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request) {
            return;
        }

        $locale = $this->getLocale($request);

        try {
            $language = $this->container->get('craft_keen.translation.provider.language')->getLanguage($locale);
            $locale = $language->getCode();
            setlocale(LC_ALL, $locale);
        } catch (LanguageNotFoundException $e) {
            $locale = null;
        }

        if ($locale) {
            $this->setLocale($locale, $request);

            return;
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function getLocale(Request $request)
    {
        if ($request->get('_locale')) {
            return $request->get('_locale');
        }

        return $this->getSession()->get('_locale', $this->container->getParameter('kernel.default_locale'));
    }

    /**
     * @param $locale
     * @param Request $request
     */
    protected function setLocale($locale, Request $request)
    {
        if ($request) {
            $request->attributes->set('_locale', $locale);
            $request->setDefaultLocale($locale);
            $request->setLocale($locale);
        }
        if (null !== $this->getRouter()) {
            $this->getRouter()->getContext()->setParameter('_locale', $locale);
        }

        $this->getSession()->set('_locale', $locale);

        \Locale::setDefault($locale);
    }

    /**
     * @return RouterInterface
     */
    protected function getRouter()
    {
        if ($this->router === false) {
            $this->router = $this->container->get('router', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        }

        return $this->router;
    }

    /**
     * @return object|Session
     */
    protected function getSession()
    {
        return $this->container->get('session');
    }
}
