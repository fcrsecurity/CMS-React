<?php

namespace CraftKeen\Bundle\TranslationBundle\Command;

use CraftKeen\Bundle\TranslationBundle\Registry\TranslationProviderInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugTranslationRegistryCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('craft:debug:translation:registry');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $providers = $this->getContainer()->get('craft_keen.translation.registry')->getProviders();

        $table = new Table($output);
        $table->setHeaders(['Alias', 'Class'])->setRows([]);

        /**
         * @var string $alias
         * @var TranslationProviderInterface $provider
         */
        foreach ($providers as $alias => $provider) {
            $table->addRow([$alias, get_class($provider)]);
        }
        $table->render();
    }
}
