<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Import\PropertyImport;

class ImportLeasingPropertiesCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:import:leasing-properties')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('max_execution_time', 500);
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $import = new PropertyImport($em);
        $import->setCsvParsingOptions([
            'finder_in' => $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/tmp_import/',
        ]);

        $output->writeln('Importing Leasing Properties');
        $message = 'Imported: ';
        try {
            $import->loadAndConverCSVFiles(
                [
                    'managers' => 'managers.csv',
                    'tenants' => 'tenants.csv',
                    'properties' => 'properties.csv',
                    'propertiesManagers' => 'property_contact.csv',
                    'propertiesGallery' => 'property_gallery.csv',
                    'propertiesTenants' => 'property_top_tenants.csv',
                ]
            );
            $import->loadDependencies();
            $response = $import->loadProperties();
            $message .= 'Properties:'.$response['properties'];
			$response = $import->translate();
            $message .= 'Properties Translated: '.$response;

        } catch (Exception $e) {
            $output->writeln('Caught exception: '. $e->getMessage());
        }
        $output->writeln('Message: '.$message);
        $output->writeln('============');
    }
}
