<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171212132458 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //cleanup
        $this->addSql('DELETE FROM leasing_brochure_plan');
        $this->addSql('DELETE FROM leasing_brochure_demographic');
        $this->addSql('DELETE FROM leasing_brochure');

        $this->addSql('CREATE TABLE leasing_brochure_images (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, image_meta LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', image_crop LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leasing_brochure_demographic ADD image_id INT DEFAULT NULL, DROP image, DROP image_crop, DROP image_meta');
        $this->addSql('ALTER TABLE leasing_brochure_demographic ADD CONSTRAINT FK_DDF0590B3DA5256D FOREIGN KEY (image_id) REFERENCES leasing_brochure_images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DDF0590B3DA5256D ON leasing_brochure_demographic (image_id)');
        $this->addSql('ALTER TABLE leasing_brochure ADD hero_image_id INT DEFAULT NULL, ADD contact_image_id INT DEFAULT NULL, ADD contact_lifestyle_image_id INT DEFAULT NULL, DROP image, DROP image_meta, DROP image_crop, DROP contact_image, DROP contact_image_meta, DROP contact_image_crop, DROP contact_lifestyle_image, DROP contact_lifestyle_image_meta, DROP contact_lifestyle_image_crop');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74C98BB94C5 FOREIGN KEY (hero_image_id) REFERENCES leasing_brochure_images (id)');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74CA780B9FA FOREIGN KEY (contact_image_id) REFERENCES leasing_brochure_images (id)');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74CAD2751B4 FOREIGN KEY (contact_lifestyle_image_id) REFERENCES leasing_brochure_images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E74C74C98BB94C5 ON leasing_brochure (hero_image_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E74C74CA780B9FA ON leasing_brochure (contact_image_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E74C74CAD2751B4 ON leasing_brochure (contact_lifestyle_image_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_brochure_demographic DROP FOREIGN KEY FK_DDF0590B3DA5256D');
        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74C98BB94C5');
        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74CA780B9FA');
        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74CAD2751B4');
        $this->addSql('DROP TABLE leasing_brochure_images');
        $this->addSql('DROP INDEX UNIQ_7E74C74C98BB94C5 ON leasing_brochure');
        $this->addSql('DROP INDEX UNIQ_7E74C74CA780B9FA ON leasing_brochure');
        $this->addSql('DROP INDEX UNIQ_7E74C74CAD2751B4 ON leasing_brochure');
        $this->addSql('ALTER TABLE leasing_brochure ADD image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD image_meta LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD image_crop LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD contact_image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD contact_image_meta LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD contact_image_crop LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD contact_lifestyle_image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD contact_lifestyle_image_meta LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD contact_lifestyle_image_crop LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, DROP hero_image_id, DROP contact_image_id, DROP contact_lifestyle_image_id');
        $this->addSql('DROP INDEX UNIQ_DDF0590B3DA5256D ON leasing_brochure_demographic');
        $this->addSql('ALTER TABLE leasing_brochure_demographic ADD image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD image_crop LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD image_meta LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', DROP image_id');
    }
}
