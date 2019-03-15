<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\Bundle\SearchBundle\Indexer\DatabaseSearchIndexer;
use CraftKeen\FCRBundle\Service\JobPositionSearchConverter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\CareersPosition;

class JobPositionSearchIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:job-position:search-index')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting Job Position Search Index');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var JobPositionSearchConverter $converter */
        $converter = $this->getContainer()->get('craft_keen.job_position.search.converter');

        /** @var DatabaseSearchIndexer $searchIndexer */
        $searchIndexer = $this->getContainer()->get('craft_keen.search.indexer');

        $careerPositions = $em->getRepository(CareersPosition::class)->findAll();
        /** @var CareersPosition $careerPosition */
        foreach ( $careerPositions as $careerPosition ) {
            if ( 'ON' == $careerPosition->getState() ) {
                $searchIndexer->add($converter->convert($careerPosition));
            }
            else {
                $searchIndexer->remove($converter->convert($careerPosition));
            }
        }

        $message = 'Job Position Search Index updated';
        $output->writeln(['Message: '.$message, '============']);
    }

}

