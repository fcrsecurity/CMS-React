<?php

namespace CraftKeen\Bundle\RevisionBundle\Command;

use CraftKeen\Bundle\RevisionBundle\Model\RevisionProviderInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugRevisionRegistryCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('craft:debug:revision:registry');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $providers = $this->getContainer()->get('craft_keen.revision.registry')->getProviders();

        $table = new Table($output);
        $table->setHeaders(['Alias', 'Class'])->setRows([]);

        /**
         * @var string $alias
         * @var RevisionProviderInterface $provider
         */
        foreach ($providers as $alias => $provider) {
            $table->addRow([$alias, get_class($provider)]);
        }
        $table->render();
    }
}
