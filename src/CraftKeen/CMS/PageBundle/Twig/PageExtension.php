<?php

namespace CraftKeen\CMS\PageBundle\Twig;

use CraftKeen\CMS\PageBundle\Entity\Page;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PageExtension extends \Twig_Extension
{
    /**
    * @var ContainerInterface
    */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('renderPageLayout', [$this, 'renderPageLayoutFilter']),
            new \Twig_SimpleFilter('serializedToForm', [$this, 'serializedToFormFilter']),
            new \Twig_SimpleFilter('jsonDecode', [$this, 'jsonDecodeFilter']),
            new \Twig_SimpleFilter('routeExists', [$this, 'routeExistsFilter']),
            new \Twig_SimpleFilter('classOf', [$this, 'classOfFilter']),
            new \Twig_SimpleFilter('unserialize', [$this, 'unserializeFilter']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('findPagePermalinkByName', [$this, 'findPagePermalinkByName']),
        ];
    }

    public function findPagePermalinkByName($name)
    {
        /** @var Page $page */
        $page = $this->container->get('doctrine')->getRepository(Page::class)
            ->findOneBy([
                'name' =>$name,
                'lang' => $this->container->get('craft_keen.translation.provider.language')->getCurrentLanguage(),
                'status' => 'live'
            ]);
        if (null !== $page) {
            return $page->getSlug();
        }
        return '/';
    }

    /**
     * Checks if route exists
     * 
     * @param string $route
     * @return boolean
     */
    public function routeExistsFilter($route)
    {
        $router = $this->container->get('router');
        return (null === $router->getRouteCollection()->get($route)) ? false : true;
    }

    /**
     * @param mixed $layoutJson
     * @param null $widgets
     *
     * @return string
     */
    public function renderPageLayoutFilter($layoutJson, $widgets = null)
    {
        $layout = json_decode($layoutJson);
        $html = $this->renderLayoutHtmlRecursively($layout, $widgets);

        return $html;
    }

    /**
     * @param $serializedString
     * @return string
     */
    public function serializedToFormFilter($serializedString)
    {
        $html = '';
        $unsar = unserialize($serializedString);
        $html = $this->renderInputRecursive($unsar, $html, 'unsar');

        return $html;
    }

    /**
     * @param string $json
     *
     * @return mixed
     */
    public function jsonDecodeFilter($json = '')
    {
        $object = json_decode($json, true);

        return $object;
    }

    /**
     * @param $objectClass
     * @param $className
     * @return bool
     */
    public function classOfFilter($objectClass, $className)
    {
        if ( strpos($objectClass, $className) ) {
            return true;
        }
        return false;
    }

    /**
     * @param $serializedString
     * @return mixed
     */
    public function unserializeFilter($serializedString)
    {
        return unserialize($serializedString);
    }

    /**
     * @param $layout
     * @param null $widgets
     * @param int $parent
     * @param string $parentID
     *
     * @return string
     */
    private function renderLayoutHtmlRecursively($layout, &$widgets = null, $parent = 0, $parentID = '')
    {
        $rowCount = $parent + 1;

        $html = '';
        if (count($layout) > 0) {
            $html .= '<div class="page-layout-block">';

            foreach ($layout as $row) {
                $rowId = $parentID . 'R' . $rowCount;
                $html .= '<div id="' . $rowId . '" class="row">';
                $colCount = 1;
                foreach ($row as $column) {
                    $colId = $rowId . 'C' . $colCount;
                    $html .= '<div id="' . $colId . '" class="cols ' . $column->class . '">';
                    if (is_string($column->content)) {
                        $html .= $column->content;
                        //$this->findWidgets( $column->content, $widgets );
                    }
                    if (is_object($column->content)) {
                        $html .= $this->renderLayoutHtmlRecursively($column->content, $widgets, $parent, $colId);
                    }
                    $html .= '</div>';
                    $colCount++;
                }
                $html .= '</div>';
                $rowCount++;
            }
            $html .= '</div>';
        }

        return $html;
    }

    private function renderInputRecursive($array, $html = '', $inputParentName='')
    {
        $html .= '<ul>';
        foreach ($array as $key => $items) {
            $pn = $inputParentName.'['.$key.']';
            if (is_array($items)) {
                $html .= '<li><b>'.$key.':</b>'.$this->renderInputRecursive($items, '', $pn).'</li>';
            } else {
                $html .= '<li><label>'.$key.'</label> : <input type="text" name="'.$pn.'" value="'.$items.'"/></li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }
}
