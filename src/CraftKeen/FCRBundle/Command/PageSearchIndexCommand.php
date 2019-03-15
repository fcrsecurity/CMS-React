<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\Bundle\SearchBundle\Indexer\DatabaseSearchIndexer;
use CraftKeen\FCRBundle\Service\PageSearchConverter;

class PageSearchIndexCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:page:search-index')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting Page Search Index');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var PageSearchConverter $converter */
        $converter = $this->getContainer()->get('craft_keen.page.search.converter');

        /** @var DatabaseSearchIndexer $searchIndexer */
        $searchIndexer = $this->getContainer()->get('craft_keen.search.indexer');

        $pages = $em->getRepository(Page::class)->findAll();

        foreach ( $pages as $page ) {

            if ( 'live' == $page->getStatus() && $page->getIsIndexed() ) {
                $searchIndexer->add($converter->convert($page));
            }
            if ((!$page->getIsIndexed() || 'live' !== $page->getStatus())) {
                $searchIndexer->remove($converter->convert($page));
            }
        }

        $message = 'Page Search Index updated';
        $output->writeln(['Message: '.$message, '============']);
    }
}
