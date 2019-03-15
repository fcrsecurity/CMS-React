<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;
use CraftKeen\FCRBundle\Entity\Property;

class PropertySearchIndexCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:property:search-index')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting Property Search Index');
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $searchIndexer = $this->getContainer()->get('craft_keen.search.indexer');

        $properties = $em->getRepository(Property::class)->findAll();

        foreach ( $properties as $property ) {

            if ( 'live' == $property->getStatus() && null !== $property->getDetails() && !$property->getIsHidden() ) {
                $searchIndexer->add($this->convert($property));
            }
            if (($property->getIsHidden() || 'live' !== $property->getStatus()) && null !== $property->getDetails()) {
                $searchIndexer->remove($this->convert($property));
            }
        }

        $message = 'Property Search Index updated';
        $output->writeln(['Message: '.$message, '============']);
    }

    /**
     * TODO: Move it to a proper place.
     *
     * @param Property $property
     *
     * @return SearchItem
     */
    private function convert(Property $property)
    {
        $model = new SearchItem();
        $name = $property->getParentName();
        $shortBody = '';
        if ( null !== $property->getDetails() && strlen($property->getDetails()->getGeoAddress1()) > 0 ) {
            $shortBody .= $property->getDetails()->getGeoAddress1();
        }

        if ( null !== $property->getChildName() && strlen($property->getChildName()) > 0 && strtolower($name) != strtolower($property->getChildName()) ) {
            $name .= ' - '.$property->getChildName();
        }
        if ( null !== $property->getDetails()->getGeoAddress2() && strlen($property->getDetails()->getGeoAddress2()) > 0 ) {
            $shortBody .= '<br>'.$property->getDetails()->getGeoAddress2();
        }
        if ( null !== $property->getDetails()->getDescription() && strlen($property->getDetails()->getDescription()) > 0 ) {
            $shortBody .= '<br>'.$property->getDetails()->getDescription();
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

        $hiddenMeta .= ' '.$property->getDetails()->getGeoIntersetion();
        $hiddenMeta .= ' '.$property->getDetails()->getGeoPostal();

        return $model->setWeight($property->getSortOrder())
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
