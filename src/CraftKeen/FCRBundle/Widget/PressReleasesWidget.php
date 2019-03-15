<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\PressRelease;

class PressReleasesWidget extends AbstractWidget implements
    DoctrineAwareInterface,
    LanguageProviderAwareInterface
{
    use DoctrineAwareTrait, LanguageProviderAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getTemplateData()
    {
        return array_merge(parent::getTemplateData(), ['DataPressReleases' => $this->getData()]);
    }

    /**
     * @return array|PressRelease[]
     */
    protected function getData()
    {
        $articles = [];
        $findBy = ['lang' => $this->getCurrentLanguage(),'status' => 'live',];

        $pressRelease = $this->getRepository(PressRelease::class)->findBy($findBy, ['date' => 'DESC']);

        /** @var PressRelease $item */
        foreach ($pressRelease as $item) {
            $year = $item->getDate()->format('Y');
            $articles[$year][] = $item;
        }

        krsort($articles, SORT_NUMERIC);

        return $articles;
    }
}
