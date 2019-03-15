<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\CareersEmployee;
use CraftKeen\FCRBundle\Repository\CareersEmployeeRepository;

class CareersPersonBlockMacroWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['employees' => $this->getData()]);
    }

    /**
     * Return random 4 employees from all listing.
     * @return array|CareersEmployee[]
     */
    protected function getData()
    {
        /** @var CareersEmployeeRepository $repo */
        $repo = $this->getRepository(CareersEmployee::class);

        return $repo->findRand($this->getCurrentLanguage(), null, 'live', 4);
    }

    /**
     * @return bool
     */
    public function supportsCache()
    {
        return false;
    }
}
