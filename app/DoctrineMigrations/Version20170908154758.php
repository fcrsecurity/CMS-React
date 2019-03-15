<?php

namespace Application\Migrations;

use CraftKeen\FCRBundle\Entity\PropertyFeatureSlider;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170908154758 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $sliders = $em->getRepository(PropertyFeatureSlider::class)->findAll();

        /** @var PropertyFeatureSlider $manager */
        foreach ($sliders as $slider) {
            $access = unserialize($slider->getAccess());
            $access['UPDATE'][] = 'ROLE_LEASING_REGIONAL_COORDINATORS';
            $slider->setAccess(serialize($access));
            $em->persist($slider);
        }

        $em->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $sliders = $em->getRepository(PropertyFeatureSlider::class)->findAll();

        /** @var PropertyFeatureSlider $manager */
        foreach ($sliders as $slider) {
            $access = unserialize($slider->getAccess());

            foreach ($access['UPDATE'] as $key => $role) {
                if ($role == 'ROLE_LEASING_REGIONAL_COORDINATORS') {
                    unset($access['UPDATE'][$key]);
                }
            }

            $slider->setAccess(serialize($access));
            $em->persist($slider);
        }

        $em->flush();
    }
}
