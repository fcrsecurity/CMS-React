<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\Office;

class OtherOfficesWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['offices' => $this->getData()]);
    }

    /**
     * @return array|Office[]
     */
    protected function getData()
    {
        return $this->getRepository(Office::class)
            ->findBy(
                [
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live', //TODO: Should be config value ???
                    'isMain' => false, //TODO: Should be config value
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }
}
