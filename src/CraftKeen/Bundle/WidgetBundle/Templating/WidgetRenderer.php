<?php

namespace CraftKeen\Bundle\WidgetBundle\Templating;

use CraftKeen\Bundle\WidgetBundle\Model\WidgetInterface;
use CraftKeen\CMS\ThemeBundle\Provider\ThemeProvider;
use Doctrine\Common\Cache\CacheProvider;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class WidgetRenderer
{
    /** @var EngineInterface */
    protected $templating;

    /** @var CacheProvider */
    protected $cache;

    /**
     * @param EngineInterface $templating
     * @param CacheProvider $cache
     * @param ThemeProvider $themeProvider
     * @param bool $isCacheAllowed
     */
    public function __construct(
        EngineInterface $templating,
        CacheProvider $cache,
        ThemeProvider $themeProvider,
        $isCacheAllowed = true
    ) {
        $this->templating = $templating;
        $this->cache = $cache;
        $this->themeProvider = $themeProvider;
        $this->isCacheAllowed = $isCacheAllowed;
    }

    /**
     * @param WidgetInterface $widget
     * @param array $params
     *
     * @return string
     * @throws \RuntimeException
     */
    public function render(WidgetInterface $widget, $params = [])
    {
        $uid = $widget->getUid();

        if ($this->isCacheAllowed && $this->cache->contains($uid)) {
            return $this->cache->fetch($uid);
        }

        $data = $this->templating->render(
            sprintf(
                $widget->getTemplate(),
                $this->themeProvider->getCurrentThemeName()
            ),
            array_merge($widget->getTemplateData(), $params)
        );

        if ($this->isCacheAllowed && $widget->supportsCache()) {
            $this->cache->save($uid, $data);
        }

        return $data;
    }
}
