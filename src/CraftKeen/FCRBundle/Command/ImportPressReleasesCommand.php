<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Entity\PressRelease;
use CraftKeen\FCRBundle\Import\PressReleaseImport;

class ImportPressReleasesCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:import:press-releases')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        
        $import = new PressReleaseImport($em);
        $import->setCsvParsingOptions([
            'finder_in' => $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/tmp_import/',
        ]);
        
        $output->writeln('Importing Press Releases');
        $message = '';
        try {
            // 1. Load all CSVFiles.
            $import->loadAndConverCSVFiles(
                [
                    'press_releases' => 'press_releaeses.csv',
                ]
            );
            // 2. Clean-up Tables
            $import->eraseTables();
            // 3. Added Dependencies
            $message = $import->loadDependencies();
        } catch (Exception $e) {
            $output->writeln('Caught exception: '. $e->getMessage());
        }
        $output->writeln(['Message: '.$message, '============']);
    }
}
