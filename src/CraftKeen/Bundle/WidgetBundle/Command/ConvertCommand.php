<?php

namespace CraftKeen\Bundle\WidgetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class ConvertCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure(); // TODO: Change the autogenerated stub
        $this->setName('xxx:convert:widgets');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $this->getContainer()->get('kernel')
            ->locateResource('@CraftKeenCMSThemeBundle/Resources/views/FCR/PageWidgets');
        $outDir = $this->getContainer()->get('kernel')
            ->locateResource('@CraftKeenWidgetBundle/Model');
        $finder = new Finder();
        $files = $finder->in($dir)->files();
        $template = $this->getContainer()->get('templating');
        $ymlFile = $this->getContainer()->get('kernel')
            ->locateResource('@CraftKeenWidgetBundle/Resources/config/widgets.yml');
        $yml = Yaml::parse(file_get_contents($ymlFile));
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $nameOrig = str_replace('.html.twig', '', $file->getFilename());
            $name = ucfirst($nameOrig);
            $outModel = sprintf('%sWidget', $name);
            $outModelFile = sprintf('%s/%s.php', $outDir, $outModel);
            if (!file_exists($outModelFile)) {
                var_dump($outModelFile);
                file_put_contents($outModelFile, $template->render(
                    'CraftKeenWidgetBundle:Command:widgetClass.html.twig',
                    ['className' => $outModel]
                ));
            }
            $serviceName = sprintf("craft_keen_widget.widget.%s", strtolower($name));
            if (!isset($yml['services'][$serviceName])) {
                $yml['services'][$serviceName] = [
                    'parent' => 'craft_keen_widget.widget.abstract',
                    'class' => sprintf('CraftKeen\Bundle\WidgetBundle\Model\%s', $outModel),
                    'calls' => [
                        [
                            'setTemplate',
                            ['CraftKeenCMSThemeBundle:%s:' . sprintf('PageWidgets/%s.html.twig', $nameOrig)],
                        ],
                    ],
                    'tags' => [[
                        'name' => 'craft_keen_widget.widget',
                        'alias' => $name,
                    ]],
                ];
            }
        }
        file_put_contents($ymlFile, Yaml::dump($yml, 4, 4));
    }
}
