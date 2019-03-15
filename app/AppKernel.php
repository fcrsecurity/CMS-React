<?php

use Akuma\Bundle\DistributionBundle\AkumaKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends AkumaKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new FOS\UserBundle\FOSUserBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            new CraftKeen\CMS\AdminBundle\CraftKeenCMSAdminBundle(),
            new CraftKeen\CMS\UserBundle\CraftKeenCMSUserBundle(),
            new CraftKeen\CMS\PageBundle\CraftKeenCMSPageBundle(),
            new CraftKeen\CMS\ThemeBundle\CraftKeenCMSThemeBundle(),
            new Limenius\ReactBundle\LimeniusReactBundle(),
            new CraftKeen\FCRBundle\CraftKeenFCRBundle(),
            new CraftKeen\CMS\MenuBundle\CraftKeenCMSMenuBundle(),

            new CraftKeen\Bundle\UserBundle\CraftKeenUserBundle(),
            new CraftKeen\BrochureBuilderBundle\BrochureBuilderBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return parent::registerBundles($bundles);
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
