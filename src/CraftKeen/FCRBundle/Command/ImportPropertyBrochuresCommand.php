<?php

namespace CraftKeen\FCRBundle\Command;

use CraftKeen\FCRBundle\Import\PropertyBrochureImport;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPropertyBrochuresCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:import:property-brochures')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        
        $import = new PropertyBrochureImport($em);
        $import->setCsvParsingOptions([
            'finder_in' => $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/tmp_import/',
        ]);
        
        $output->writeln('Importing Property Brochures');
        $message = '';
        try {
            // 1. Load all CSVFiles.
            $import->loadAndConverCSVFiles(
                [
                    'property_brochures' => 'property_brochures.csv',
                ]
            );
            // 2. Clean-up Tables
            $import->eraseTables();
            // 3. Added Dependencies
            $message = $import->loadDependencies();
        } catch (Exception $e) {
            $output->writeln('Caught exception: '. $e->getMessage());
        }

        $output->writeln([
            'Added: '.count($message['added']),
            'Updated: '.count($message['updated']),
            'Wrong Property Code: '.count($message['wrong_property_code']),
            '============']);
    }
}
