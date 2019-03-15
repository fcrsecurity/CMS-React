<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\SustainabilitySlider;

class SustainabilitySliderMacroWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['sliders' => $this->getData()]);
    }

    /**
     * @return array|SustainabilitySlider
     */
    protected function getData()
    {
        return $this->getRepository(SustainabilitySlider::class)
            ->findBy(
                ['lang' => $this->getCurrentLanguage(), 'status' => 'live',],
                ['sortOrder' => 'ASC',]
            );
    }
}
