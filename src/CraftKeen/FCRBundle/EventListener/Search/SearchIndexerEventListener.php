<?php

namespace CraftKeen\FCRBundle\EventListener\Search;

use CraftKeen\Bundle\SearchBundle\Indexer\SearchIndexerInterface;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;
use CraftKeen\FCRBundle\Entity\Property;

class SearchIndexerEventListener
{
    /** @var SearchIndexerInterface */
    protected $searchIndexer;

    /**
     * @param SearchIndexerInterface $searchIndexer
     */
    public function __construct(SearchIndexerInterface $searchIndexer)
    {
        $this->searchIndexer = $searchIndexer;
    }

    /**
     * @param Property $property
     */
    public function postPersist(Property $property)
    {
        if ( 'live' == $property->getStatus() && null !== $property->getDetails() && !$property->getIsHidden() ) {
            $this->searchIndexer->add($this->convert($property));
        }
    }

    /**
     * @param Property $property
     */
    public function postUpdate(Property $property)
    {
        if ( 'live' == $property->getStatus() && null !== $property->getDetails() && !$property->getIsHidden() ) {
            $this->searchIndexer->add($this->convert($property));
        }

        // Remove hidden properties from the Search Index
        if ( 'live' == $property->getStatus() && $property->getIsHidden() ) {
            $this->postRemove($property);
        }
    }

    /**
     * @param Property $property
     */
    public function postRemove(Property $property)
    {
        if ( 'live' == $property->getStatus() ) {
            $this->searchIndexer->remove($this->convert($property));
        }
    }

    /**
     * @param Property $property
     *
     * @return SearchItem
     */
    protected function convert(Property $property)
    {
        $model = new SearchItem();
        $name = $property->getParentName();
        $shortBody = '';

        if (null !== $property->getDetails()) {
            if (null !== $property->getDetails()->getGeoAddress1() && strlen($property->getDetails()->getGeoAddress2()) > 0) {
                $shortBody = $property->getDetails()->getGeoAddress1();
            }

            if (null !== $property->getChildName() && strlen($property->getChildName()) > 0 && strtolower($name) != strtolower($property->getChildName())) {
                $name .= ' - ' . $property->getChildName();
            }
            if (null !== $property->getDetails()->getGeoAddress2() && strlen($property->getDetails()->getGeoAddress2()) > 0) {
                $shortBody .= '<br>' . $property->getDetails()->getGeoAddress2();
            }
            if (null !== $property->getDetails()->getDescription() && strlen($property->getDetails()->getDescription()) > 0) {
                $shortBody .= '<br>' . $property->getDetails()->getDescription();
            }
        }

        $hiddenMeta = $property->getCode();
        $managers = $property->getManagers();
        foreach ($managers as $manager) {
            $hiddenMeta .= ' '.$manager->getFirstName();
            $hiddenMeta .= ' '.$manager->getLastName();
        }
        $tenants = $property->getTenants();
        foreach ($tenants as $tenant) {
            $hiddenMeta .= ' '.$tenant->getName();
        }

        if (null !== $property->getDetails()) {
            $hiddenMeta .= ' ' . $property->getDetails()->getGeoIntersetion();
            $hiddenMeta .= ' ' . $property->getDetails()->getGeoPostal();
        }

        $name = ( is_null( $name ) ? ' ' : $name );
        $weight = ( is_null( $property->getSortOrder() ) ? 0 : $property->getSortOrder() );
        return $model->setWeight($weight)
            ->setLanguage($property->getLang())
            ->setSite($property->getSite())
            ->setTitle($name)
            ->setObjectClass(Property::class)
            ->setObjectId($property->getId())
            ->setShortBody($shortBody)
            ->setHiddenMeta($hiddenMeta)
            ;
    }
}