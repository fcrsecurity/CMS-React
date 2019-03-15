<?php

namespace CraftKeen\FCRBundle\Widget;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\DoctrineAwareTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use CraftKeen\Bundle\TranslationBundle\Traits\LanguageProviderAwareTrait;
use CraftKeen\Bundle\WidgetBundle\Model\AbstractWidget;
use CraftKeen\FCRBundle\Entity\PressRelease;

class LastPressReleasesWidget extends AbstractWidget implements
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
     * @return array|PressRelease[]
     */
    protected function getData()
    {
        $articles = [];
        $findBy = [
            'lang' => $this->getCurrentLanguage(),
            'status' => 'live',
        ];

        $pressRelease = $this->getRepository(PressRelease::class)
            ->findBy($findBy, ['date' => 'DESC'], 3);

        /** @var PressRelease $item */
        foreach ($pressRelease as $item) {
            $articles[] = [
                'date' => $item->getDate()->format('M d, Y'),
                'description' => $item->getTitle(),
                'href' => '/investors/press-release/' . $item->getSlug(),
            ];
        }

        return $articles;
    }
}
