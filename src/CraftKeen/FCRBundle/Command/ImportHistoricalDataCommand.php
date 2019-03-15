<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Import\HistoricalDataImport;

class ImportHistoricalDataCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:import:historical-data')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        
        $import = new HistoricalDataImport($em);
        $import->setCsvParsingOptions([
            'finder_in' => $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/tmp_import/',
        ]);

        $output->writeln('Importing Historical Data');
        $message = '';
        try {
            $import->loadAndConverCSVFiles(
                [
                    'historical_data' => 'historical_data.csv',
                    'historical_data_dividends' => 'historical_data_dividends.csv',
                ]
            );
            $import->eraseTables();
            $response = $import->loadDependencies();
            $message .= " ".$response['historical_data']." historical data records were imported | ";
            $message .= " ".$response['historical_data_dividends']." historical data Dividends records were imported";
        } catch (Exception $e) {
            $output->writeln('Caught exception: ' . $e->getMessage());
        }
        $output->writeln(['Message: ' . $message, '============']);
    }
}
