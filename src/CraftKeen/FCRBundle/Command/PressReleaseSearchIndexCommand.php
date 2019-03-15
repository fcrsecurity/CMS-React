<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\Bundle\SearchBundle\Indexer\DatabaseSearchIndexer;
use CraftKeen\FCRBundle\Service\PressReleaseSearchConverter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\PressRelease;

class PressReleaseSearchIndexCommand extends ContainerAwareCommand
{
	protected function configure()
    {
        $this
            ->setName('ckcms:fcr:press-release:search-index')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting PressRelease Search Index');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var PressReleaseSearchConverter $converter */
        $converter = $this->getContainer()->get('craft_keen.press_release.search.converter');

        /** @var DatabaseSearchIndexer $searchIndexer */
        $searchIndexer = $this->getContainer()->get('craft_keen.search.indexer');

        $pressReleases = $em->getRepository(PressRelease::class)->findAll();

        /** @var PressRelease $pressRelease */
        foreach ( $pressReleases as $pressRelease ) {
            if ( 'live' == $pressRelease->getStatus() && !$pressRelease->getIsHidden() ) {
                $searchIndexer->add($converter->convert($pressRelease));
            }
            if ( $pressRelease->getIsHidden() || 'live' !== $pressRelease->getStatus() ) {
                $searchIndexer->remove($converter->convert($pressRelease));
            }
        }

        $message = 'PressRelease Search Index updated';
        $output->writeln(['Message: '.$message, '============']);
    }

}