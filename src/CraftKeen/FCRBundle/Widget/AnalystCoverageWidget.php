<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\AnalystCoverage;

class AnalystCoverageWidget extends AbstractWidget implements DoctrineAwareInterface, LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        $items = $this->registry->getRepository(AnalystCoverage::class)->findBy([
            'lang' => $this->languageProvider->getCurrentLanguage(),
            'status' => 'live',
        ]);

        $types = [];
        foreach ($items as $item) {
            if (!in_array($item->getType(), $types)) {
                $types[] = $item->getType();
            }
        }

        return array_merge(
            parent::getTemplateData(),
            [
                'ac' => [
                    'types' => $types,
                    'items' => $items,
                ],
            ]
        );
    }
}
