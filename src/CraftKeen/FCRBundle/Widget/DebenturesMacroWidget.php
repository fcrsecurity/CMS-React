<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\Debenture;

class DebenturesMacroWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['items' => $this->getData()]);
    }

    /**
     * @return array|Debenture[]
     */
    protected function getData()
    {
        $items = $this->getRepository(Debenture::class)->findBy(['status' => 'live'], ['maturityDate' => 'ASC']);

        $debentures = [];
        foreach ($items as $d) {
            $series = $d->getSeries();
            $debentures[$series] = $d;
        }

        return $debentures;
    }
}
