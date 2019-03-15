<?php

namespace Application\Migrations;

use CraftKeen\FCRBundle\Entity\Manager;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\Tenant;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170831140513 extends AbstractMigration implements ContainerAwareInterface
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

        $managers = $em->getRepository(Manager::class)->findAll();

        /** @var Manager $manager */
        foreach ($managers as $manager) {
            $access = unserialize($manager->getAccess());
            $access['UPDATE'][] = 'ROLE_LEASING_REGIONAL_COORDINATORS';
            $manager->setAccess(serialize($access));
            $em->persist($manager);
        }

        $tenants = $em->getRepository(Tenant::class)->findAll();

        /** @var Tenant $tenant */
        foreach ($tenants as $tenant) {
            $access = unserialize($tenant->getAccess());
            $access['UPDATE'][] = 'ROLE_LEASING_REGIONAL_COORDINATORS';
            $tenant->setAccess(serialize($access));
            $em->persist($tenant);
        }

        $properties = $em->getRepository(Property::class)->findAll();

        /** @var Property $property */
        foreach ($properties as $property) {
            $access = $property->getAccess();
            if (strlen($access) > 0) {
                $access = unserialize($access);
                $access['UPDATE'][] = 'ROLE_LEASING_REGIONAL_COORDINATORS';
                $property->setAccess(serialize($access));
                //$em->persist($property);
                $em->flush($property);
            } else {
                $access = [
                    'CREATE' => null,
                    'READ' => null,
                    'UPDATE' => ['ROLE_LEASING', 'ROLE_LEASING_REGIONAL_COORDINATORS'],
                    'DELETE' => null,
                    'APPROVE' => ['ROLE_LEASING'],
                ];
                $property->setAccess(serialize($access));
                $em->flush($property);
            }


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

        $managers = $em->getRepository(Manager::class)->findAll();

        /** @var Manager $manager */
        foreach ($managers as $manager) {
            $access = unserialize($manager->getAccess());

            foreach ($access['UPDATE'] as $key => $role) {
                if ($role == 'ROLE_LEASING_REGIONAL_COORDINATORS') {
                    unset($access['UPDATE'][$key]);
                }
            }

            $manager->setAccess(serialize($access));
            $em->persist($manager);
        }

        $tenants = $em->getRepository(Tenant::class)->findAll();

        /** @var Tenant $tenant */
        foreach ($tenants as $tenant) {
            $access = unserialize($tenant->getAccess());

            foreach ($access['UPDATE'] as $key => $role) {
                if ($role == 'ROLE_LEASING_REGIONAL_COORDINATORS') {
                    unset($access['UPDATE'][$key]);
                }
            }

            $tenant->setAccess(serialize($access));
            $em->persist($tenant);
        }

        $properties = $em->getRepository(Property::class)->findAll();

        /** @var Property $property */
        foreach ($properties as $property) {
            $access = unserialize($property->getAccess());

            foreach ($access['UPDATE'] as $key => $role) {
                if ($role == 'ROLE_LEASING_REGIONAL_COORDINATORS') {
                    unset($access['UPDATE'][$key]);
                }
            }

            $property->setAccess(serialize($access));
            $em->persist($property);
        }

        $em->flush();
    }
}
