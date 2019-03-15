<?php

namespace CraftKeen\Bundle\ComponentBundle\Twig;

use CraftKeen\Bundle\ComponentBundle\Provider\EntityLinkProvider;

class EntityLinkExtension extends \Twig_Extension
{
    /** @var EntityLinkProvider */
    protected $entityLinkProvider;

    /**
     * @param EntityLinkProvider $entityLinkProvider
     */
    public function __construct(EntityLinkProvider $entityLinkProvider)
    {
        $this->entityLinkProvider = $entityLinkProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('entity_slug_link', [$this->entityLinkProvider, 'getSlugLink'], ['is_safe' => ['all']]),
            new \Twig_Filter('entity_view_link', [$this->entityLinkProvider, 'getViewLink'], ['is_safe' => ['all']]),
        ];
    }
}
