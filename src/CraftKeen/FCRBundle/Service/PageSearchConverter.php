<?php

namespace CraftKeen\FCRBundle\Service;

use Doctrine\ORM\EntityManager;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;

/**
 * @package CraftKeen\FCRBundle\Service
 *
 */
class PageSearchConverter
{
    /** @var EntityManager */
    private $em;

    /**
     * PageSearchConverter constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Page $page
     *
     * @return SearchItem
     */
    public function convert(Page $page)
    {
        $model = new SearchItem();
        $hiddenMeta = '';
        $itemsToIndex = ['title','subtitle','text','label','description','name','city'];

        $widgets = $this->em->getRepository(PageWidget::class)->findBy(['page' => $page]);
        /** @var PageWidget $widget */
        foreach ($widgets as $widget) {
            $hiddenMeta = $this->findMeta($widget->getData(), $hiddenMeta, $itemsToIndex);
        }

        return $model->setWeight(0)
            ->setLanguage($page->getLang())
            ->setSite($page->getSite())
            ->setTitle($page->getName())
            ->setObjectClass(Page::class)
            ->setObjectId($page->getId())
            ->setTitle($page->getName())
            ->setHiddenMeta($hiddenMeta)
            ->setShortBody($page->getMetaDescription() ?: $page->getName())
            ;
    }

    /**
     * @param $data
     * @param $hiddenMeta
     * @param $itemsToIndex
     * @return string
     */
    private function findMeta($data, $hiddenMeta, $itemsToIndex)
    {
        $data = unserialize($data);
        if ($data) {
            foreach ($itemsToIndex as $indexItem) {
                $this->recursiveArraySearchByKey($indexItem, $data, $hiddenMeta);
            }
        }
        return $hiddenMeta;
    }

    /**
     * @param $needle
     * @param $haystack
     * @param $hiddenMeta
     * @param int $l
     */
    private function recursiveArraySearchByKey($needle, $haystack, &$hiddenMeta, $l= 1)
    {
        foreach($haystack as $key => $value) {
            $l++;
            if ( (is_array($value)) ) {
                $this->recursiveArraySearchByKey($needle, $value, $hiddenMeta, $l);
            } elseif ($needle===$key && strlen($value) > 0) {
                $hiddenMeta .= $value.' ';
            }
        }
    }
}
