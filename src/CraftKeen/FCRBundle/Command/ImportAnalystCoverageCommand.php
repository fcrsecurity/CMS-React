<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CraftKeen\FCRBundle\Import\AnalystCoverageImport;

class ImportAnalystCoverageCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:import:analyst-coverage')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $import = new AnalystCoverageImport($em);
        $import->setCsvParsingOptions([
            'finder_in' => $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/tmp_import/',
        ]);

        $output->writeln([$this->getContainer()->getParameter('kernel.root_dir') . '/Resources/tmp_import/']);

        $output->writeln('Importing Analyst Coverage Data');
        $message = '';
        try {

            $import->loadAndConverCSVFiles(
                [
                    'analyst_coverage' => 'analyst_coverage.csv',
                ]
            );
            $import->eraseTables();
            $response = $import->loadDependencies();
            $message .= " ".$response['analyst_coverage']." records were imported | ";
        } catch (Exception $e) {
            $output->writeln('Caught exception: ' . $e->getMessage());
        }
        $output->writeln(['Message: ' . $message, '============']);

    }
}
