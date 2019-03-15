<?php

namespace CraftKeen\CMS\MenuBundle\Twig;

use CraftKeen\CMS\AdminBundle\Site\SiteManager;
use CraftKeen\CMS\MenuBundle\Provider\MenuProvider;

class MenuExtension extends \Twig_Extension
{
    /** @var MenuProvider */
    protected $menuProvider;

    /** @var SiteManager */
    protected $siteManager;

    /**
     * @param MenuProvider $menuProvider
     * @param SiteManager $siteManager
     */
    public function __construct(MenuProvider $menuProvider, SiteManager $siteManager)
    {
        $this->menuProvider = $menuProvider;
        $this->siteManager = $siteManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('renderMenu', [$this, 'renderMenu'], ['needs_environment' => true]),
        ];
    }

    /**
     * @param \Twig_Environment $environment
     * @param $type
     *
     * @return string
     */
    public function renderMenu(\Twig_Environment $environment, $type)
    {
        $menuItems = $this->menuProvider->getMenuItems($type);
        $theme = $this->siteManager->getCurrentSite()->getTheme();

        return $environment->render(
            'CraftKeenCMSThemeBundle:' . $theme . ':Menu/' . $type . '.html.twig',
            [
                'menu' => $menuItems,
            ]
        );
    }
}
