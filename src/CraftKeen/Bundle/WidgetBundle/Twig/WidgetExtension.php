<?php

namespace CraftKeen\Bundle\WidgetBundle\Twig;

use CraftKeen\Bundle\WidgetBundle\Exception\WidgetModelNotFound;
use CraftKeen\Bundle\WidgetBundle\Factory\WidgetFactory;
use CraftKeen\Bundle\WidgetBundle\Templating\WidgetRenderer;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class WidgetExtension extends \Twig_Extension implements LoggerAwareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var WidgetRenderer */
    protected $widgetRenderer;

    /** @var ManagerRegistry */
    protected $registry;

    /** @var WidgetFactory */
    protected $widgetFactory;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * WidgetExtension constructor.
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('render_widget', [$this, 'renderWidget'], ['is_safe' => ['html']]),
            new \Twig_Function('render_widgets', [$this, 'renderWidgets'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param PageWidget $pageWidget
     * @param array $params
     *
     * @return string
     */
    public function renderWidget(PageWidget $pageWidget, array $params = [])
    {
        $widget = $this->widgetFactory->createWidget($pageWidget);

        return $this->getWidgetRenderer()->render($widget, $params);
    }

    /**
     * @param Page $page
     * @param null $tplArea
     *
     * @return string
     */
    public function renderWidgets(Page $page, $tplArea = null)
    {
        $repository = $this->getRegistry()->getManagerForClass(PageWidget::class)->getRepository(PageWidget::class);
        $criteria = ['page' => $page];
        if ($tplArea) {
            $criteria['tplArea'] = $tplArea;
        }
        $widgets = $repository->findBy($criteria);

        $result = '';

        foreach ($widgets as $pageWidget) {
            try {
                $widget = $this->getWidgetFactory()->createWidget($pageWidget);
                $result .= $this->getWidgetRenderer()->render($widget);
            } catch (WidgetModelNotFound $e) {
                $this->logger->critical($e->getMessage(), [
                    'pageWidget' => serialize($pageWidget),
                    'tplArea' => $tplArea,
                    'page' => serialize($page),
                ]);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Registry|object|ManagerRegistry
     */
    protected function getRegistry()
    {
        if (!$this->registry) {
            $this->registry = $this->container->get('doctrine');
        }

        return $this->registry;
    }

    /**
     * @return WidgetRenderer
     */
    protected function getWidgetRenderer()
    {
        if (!$this->widgetRenderer) {
            $this->widgetRenderer = $this->container->get('craft_keen_widget.renderer');
        }

        return $this->widgetRenderer;
    }

    /**
     * @return WidgetFactory
     */
    protected function getWidgetFactory()
    {
        if (!$this->widgetFactory) {
            $this->widgetFactory = $this->container->get('craft_keen_widget.factory');
        }

        return $this->widgetFactory;
    }
}
