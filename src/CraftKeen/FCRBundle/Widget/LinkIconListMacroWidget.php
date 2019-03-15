<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\FinancialReport;
use CraftKeen\FCRBundle\Entity\Sustainability;

class LinkIconListMacroWidget extends AbstractWidget implements
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
            'latestFinancialReport' => $this->getLatestFinancialReport(),
            'latestSustainabilityReport' => $this->getLatestSustainabilityReport(),
        ]);
    }

    /**
     * @return null|FinancialReport
     */
    protected function getLatestFinancialReport()
    {
        return $this->getRepository(FinancialReport::class)->findOneBy(
            ['lang' => $this->getCurrentLanguage(), 'status' => 'live',],
            ['year' => 'DESC',]
        );
    }

    /**
     * @return null|Sustainability
     */
    protected function getLatestSustainabilityReport()
    {
        return $this->getRepository(Sustainability::class)->findOneBy(
            ['lang' => $this->getCurrentLanguage(), 'status' => 'live',],
            ['year' => 'DESC',]
        );
    }
}
