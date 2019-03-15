<?php
namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\CareersSlider;

class CareersSliderInfoMacroWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['careers_slider' => $this->getData()]);
    }

    /**
     * @return array|CareersSlider[]
     */
    protected function getData()
    {
        return $this->getRepository(CareersSlider::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ]
            );
    }
}
