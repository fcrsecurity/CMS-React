<?php

namespace CraftKeen\CMS\PageBundle\Controller\Admin;

use CraftKeen\Bundle\WidgetBundle\Model\WidgetInterface;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\PageBundle\Entity\Widget;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Menu controller.
 *
 * @Route("widgets/")
 */
class WidgetController extends Controller
{
    /**
     * Lists all page entities.
     *
     * @Route("demo/", name="admin_widgets_demo")
     *
     * @Template()
     */
    public function demoAction()
    {
        $queryBuilder = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->addSelect('w.id')
            ->addSelect('widget.id as wid')
            ->addSelect('widget.name')
            ->addSelect('w.config')
            ->addSelect('w.data')
            ->addSelect('w.dataType')
            ->addSelect('w.tplArea')
            ->addSelect('widget.macros')
            ->from(PageWidget::class, 'w')
            ->leftJoin(Widget::class, 'widget', 'WITH', 'w.widget = widget.id')
            //->andWhere('w.dataType = :dataType')
            //->setParameter('dataType', 'BoardPersonBlockMacro')
        ;
        $widgets = $queryBuilder->getQuery()->getResult();

        VarDumper::dump(count($widgets));

        foreach ($widgets as $key => $widget) {
            switch ($widget['dataType']) {
                case 'entity':
                    $widgets[$key]['data'] = $widget['data'];
                    $widgets[$key]['config'] = unserialize($widget['config']);
                    break;

                default:
                    $widgets[$key]['data'] = unserialize($widget['data']);
                    $widgets[$key]['config'] = unserialize($widget['config']);
                    break;
            }
        }

        return [
            'widgets' => $widgets,
        ];
    }

    /**
     * Lists all page entities.
     *
     * @Route("demo-new/", name="admin_widgets_demo_new")
     *
     * @Template()
     */
    public function demoNewAction()
    {
        $widgets = $this->getDoctrine()->getRepository(PageWidget::class)->findBy(
            [
                //'dataType' => 'FeatureSlider',
            ]
        );

        $widgets = array_map(function (PageWidget $widget) {
            return $this->container->get('craft_keen_widget.factory')->createWidget($widget);
        }, $widgets);

        $widgets = array_map(function (WidgetInterface $widget) {
            return $this->container->get('craft_keen_widget.renderer')->render($widget);
        }, $widgets);

        return [
            'widgets' => $widgets,
        ];
    }
}
