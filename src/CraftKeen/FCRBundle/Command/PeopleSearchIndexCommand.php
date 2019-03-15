<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\Bundle\SearchBundle\Indexer\DatabaseSearchIndexer;
use CraftKeen\FCRBundle\Service\PeopleSearchConverter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\People;

class PeopleSearchIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:people:search-index')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting People Search Index');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var PeopleSearchConverter $converter */
        $converter = $this->getContainer()->get('craft_keen.people.search.converter');

        /** @var DatabaseSearchIndexer $searchIndexer */
        $searchIndexer = $this->getContainer()->get('craft_keen.search.indexer');

        $peoples = $em->getRepository(People::class)->findAll();
        /** @var People $people */
        foreach ( $peoples as $people ) {
            if ( 'live' == $people->getStatus() && is_null($people->getDeletedAt()) ) {
                $searchIndexer->add($converter->convert($people));
            }
            if ( !is_null($people->getDeletedAt()) || 'live' !== $people->getStatus() ) {
                $searchIndexer->remove($converter->convert($people));
            }
        }

        $message = 'People Search Index updated';
        $output->writeln(['Message: '.$message, '============']);
    }

}
