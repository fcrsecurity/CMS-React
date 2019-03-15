<?php

namespace CraftKeen\FCRBundle\Command;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SaveHistoricalPriceCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this->setName('ckcms:fcr:save-historical-price')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sync= $this->getContainer()->get('craft_keen.historical_data_sync');
        $sync->syncWithStockWatch();
    }
}
