<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\PropertyFeatureSlider;

class FeatureSliderWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), [
            'FeatureSlider' => $this->getData(),
            'page' => $this->source->getPage(),
        ]);
    }

    /**
     * @return array|PropertyFeatureSlider[]
     */
    protected function getData()
    {
        return $this->getRepository(PropertyFeatureSlider::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'page' => $this->source->getPage(),
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }
}
