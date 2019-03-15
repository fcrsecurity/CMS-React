<?php

namespace CraftKeen\FCRBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PullStockValuesCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    protected function configure()
    {
        $this
            ->setName('ckcms:fcr:pull-stock-values')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $logger = $this->getContainer()->get('logger');
        
        $handler = $this->getContainer()->get('craft_keen_fcr.service.stockwatch_handler');
        $data = $handler->handle($logger);
    }
}
