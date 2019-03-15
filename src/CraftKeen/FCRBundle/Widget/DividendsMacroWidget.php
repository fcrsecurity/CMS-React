<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\Dividend;

class DividendsMacroWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['dividends' => $this->getDividends()]);
    }

    /**
     * @return array
     */
    protected function getDividends()
    {
        $items = $this->getRepository(Dividend::class)->findBy(['status' => 'live'], ['recordDate' => 'DESC']);

        $dividends = [];
        foreach ($items as $d) {
            $date = $d->getDeclaredDate();
            $dividends[$date->format('Y')][] = $d;
        }
        krsort($dividends);

        return $dividends;
    }
}
