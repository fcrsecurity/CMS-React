<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\Bundle\SearchBundle\Indexer\DatabaseSearchIndexer;
use CraftKeen\FCRBundle\Service\FaqSearchConverter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\FAQ;

class FaqSearchIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:faq:search-index')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting FAQ Search Index');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var FaqSearchConverter $converter */
        $converter = $this->getContainer()->get('craft_keen.faq.search.converter');

        /** @var DatabaseSearchIndexer $searchIndexer */
        $searchIndexer = $this->getContainer()->get('craft_keen.search.indexer');

        $faqs = $em->getRepository(FAQ::class)->findby(['category' => 1]);
        /** @var FAQ $faq */
        foreach ( $faqs as $faq ) {
            if ( 'live' == $faq->getStatus() && is_null($faq->getDeletedAt()) ) {
                $searchIndexer->add($converter->convert($faq));
            }
            if ( !is_null($faq->getDeletedAt()) || 'live' !== $faq->getStatus() ) {
                $searchIndexer->remove($converter->convert($faq));
            }
        }

        $message = 'FAQ Search Index updated';
        $output->writeln(['Message: '.$message, '============']);
    }

}
