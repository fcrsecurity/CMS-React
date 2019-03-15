<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\Bundle\SearchBundle\Indexer\DatabaseSearchIndexer;
use CraftKeen\FCRBundle\Service\BlogSearchConverter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\RetailArt;

class BlogSearchIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:blog:search-index')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting Blog Search Index');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var BlogSearchConverter $converter */
        $converter = $this->getContainer()->get('craft_keen.blog.search.converter');

        /** @var DatabaseSearchIndexer $searchIndexer */
        $searchIndexer = $this->getContainer()->get('craft_keen.search.indexer');

        $blogAll = $em->getRepository(RetailArt::class)->findAll();
        /** @var RetailArt $blog */
        foreach ( $blogAll as $blog ) {
            if ( 'live' == $blog->getStatus() && is_null($blog->getDeletedAt()) ) {
                $searchIndexer->add($converter->convert($blog));
            }
            if ( !is_null($blog->getDeletedAt()) || 'live' !== $blog->getStatus() ) {
                $searchIndexer->remove($converter->convert($blog));
            }
        }

        $message = 'Blog Search Index updated';
        $output->writeln(['Message: '.$message, '============']);
    }

}
