<?php

namespace Application\Migrations;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\FCRBundle\Entity\PressRelease;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170725122635 extends AbstractMigration implements ContainerAwareInterface
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
        $em->getFilters()->disable('softdeleteable');

        $site = $em->getRepository(Site::class)->findOneById(1);
        $pressReleases = $em->getRepository(PressRelease::class)->findAll();

        $em->getFilters()->enable('softdeleteable');

        $double = [];
        foreach ($pressReleases as $pressRelease) {
            $double[strtolower($pressRelease->getSlug())][] = $pressRelease;
        }

        $forUpdate = [];

        foreach ($double as $slug => $items) {
            if (count($items) >= 2) {
                foreach ($items as $item) {
                    /** @var PressRelease $item */
                    $item->setSite($site);
                    $item->setSlug($slug . '-' . $item->getId());
                    $em->persist($item);
                    $em->flush($item);
                    $forUpdate[] = $item;
                }
            }
        }

        // update all nullable values
        $this->addSql('UPDATE investors_press_release SET site_id = \'1\' WHERE site_id is null;');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2764F642989D9B62 ON investors_press_release (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP INDEX UNIQ_2764F642989D9B62 ON investors_press_release');
    }
}
