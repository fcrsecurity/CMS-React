<?php

namespace CraftKeen\Bundle\WidgetBundle\Factory;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Model\SecurityContextAwareInterface;
use CraftKeen\Bundle\WidgetBundle\Exception\WidgetModelNotFound;
use CraftKeen\Bundle\WidgetBundle\Model\WidgetInterface;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class WidgetFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var array|WidgetInterface[] */
    protected $widgets = [];

    /**
     * @param PageWidget $pageWidget
     *
     * @return null|WidgetInterface
     */
    public function createWidget(PageWidget $pageWidget)
    {
        foreach ($this->widgets as $widget) {
            if ($widget->isApplicable($pageWidget)) {
                $result = clone $widget;
                $result->setSource($pageWidget);

                if ($result instanceof DoctrineAwareInterface) {
                    $widget->setRegistry($this->container->get('doctrine'));
                }

                if ($result instanceof SecurityContextAwareInterface) {
                    $result->setTokenStorage($this->container->get('security.token_storage'));
                    $result->setAuthorizationChecker($this->container->get('security.authorization_checker'));
                }

                return $result;
            }
        }

        throw new WidgetModelNotFound($pageWidget->getDataType());
    }

    /**
     * @param WidgetInterface $widget
     * @param string $alias
     */
    public function addWidget(WidgetInterface $widget, $alias)
    {
        $this->widgets[$alias] = $widget;
    }
}
