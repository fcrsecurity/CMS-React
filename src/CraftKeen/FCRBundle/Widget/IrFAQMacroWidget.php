<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\FAQ;

class IrFAQMacroWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['faq' => $this->getData()]);
    }

    /**
     * @return array|FAQ[]
     */
    protected function getData()
    {
        return $this->getRepository(FAQ::class)
            ->findBy(
                [
                    'category' => 1,
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }
}
