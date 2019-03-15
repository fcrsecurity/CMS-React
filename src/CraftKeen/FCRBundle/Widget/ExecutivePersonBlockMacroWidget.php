<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\People;

class ExecutivePersonBlockMacroWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['board_of_directors' => $this->getData()]);
    }

    /**
     * @return array|People[]
     */
    protected function getData()
    {
        return $this->getRepository(People::class)
            ->findBy(
                [
                    'category' => 2,
                    'lang' => $this->getCurrentLanguage(),
                    'status' => 'live',
                ],
                [
                    'sortOrder' => 'ASC',
                ]
            );
    }
}
