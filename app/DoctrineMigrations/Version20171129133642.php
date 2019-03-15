<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171129133642 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('DELETE FROM leasing_brochure');
        $this->addSql('CREATE TABLE leasing_brochure_demographic (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, image_crop LONGTEXT DEFAULT NULL, image_meta LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', annual_average_daily_traffic DOUBLE PRECISION DEFAULT NULL, population_1km DOUBLE PRECISION DEFAULT NULL, household_1km DOUBLE PRECISION DEFAULT NULL, household_income_1km DOUBLE PRECISION DEFAULT NULL, population_3km DOUBLE PRECISION DEFAULT NULL, household_3km DOUBLE PRECISION DEFAULT NULL, household_income_3km DOUBLE PRECISION DEFAULT NULL, population_5km DOUBLE PRECISION DEFAULT NULL, household_5km DOUBLE PRECISION DEFAULT NULL, household_income_5km DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_DDF0590BB96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leasing_brochure_demographic ADD CONSTRAINT FK_DDF0590BB96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE leasing_brochure_aerial');
        $this->addSql('DROP TABLE leasing_brochure_contact');
        $this->addSql('DROP TABLE leasing_brochure_cover');
        $this->addSql('ALTER TABLE leasing_brochure ADD city VARCHAR(255) DEFAULT NULL, ADD province VARCHAR(255) DEFAULT NULL, ADD postal VARCHAR(255) DEFAULT NULL, ADD address1 VARCHAR(255) DEFAULT NULL, ADD address2 VARCHAR(255) DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD image_meta LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD image_crop LONGTEXT DEFAULT NULL, ADD contact_image VARCHAR(255) DEFAULT NULL, ADD contact_image_meta LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD contact_image_crop LONGTEXT DEFAULT NULL, ADD contact_lifestyle_image VARCHAR(255) DEFAULT NULL, ADD contact_lifestyle_image_meta LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD contact_lifestyle_image_crop LONGTEXT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD tenants LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD contacts LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD location_latitude DOUBLE PRECISION DEFAULT NULL, ADD location_longitude DOUBLE PRECISION DEFAULT NULL, ADD intersection VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(50) DEFAULT \'draft\' NOT NULL');
        $this->addSql('ALTER TABLE leasing_brochure_plan DROP INDEX UNIQ_14A2DC65B96114D1, ADD INDEX IDX_14A2DC65B96114D1 (brochure_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE leasing_brochure_aerial (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, annual_average_daily_traffic DOUBLE PRECISION DEFAULT NULL, population_1km DOUBLE PRECISION DEFAULT NULL, household_1km DOUBLE PRECISION DEFAULT NULL, household_income_1km DOUBLE PRECISION DEFAULT NULL, population_3km DOUBLE PRECISION DEFAULT NULL, household_3km DOUBLE PRECISION DEFAULT NULL, household_income_3km DOUBLE PRECISION DEFAULT NULL, population_5km DOUBLE PRECISION DEFAULT NULL, household_5km DOUBLE PRECISION DEFAULT NULL, household_income_5km DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_6CAD2D6EB96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leasing_brochure_contact (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, image LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, lifestyle_image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, address1 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, address2 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_first_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_last_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_email VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_fax VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_phone VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, leasing_phone_extension VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, location_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, location_address1 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, location_address2 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, location_latitude VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, location_longitude VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_99F3609BB96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leasing_brochure_cover (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, city VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, province VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, tagline VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, tenants LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_9EADE614B96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leasing_brochure_aerial ADD CONSTRAINT FK_6CAD2D6EB96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure_contact ADD CONSTRAINT FK_99F3609BB96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure_cover ADD CONSTRAINT FK_9EADE614B96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE leasing_brochure_demographic');
        $this->addSql('ALTER TABLE leasing_brochure DROP city, DROP province, DROP postal, DROP address1, DROP address2, DROP image, DROP image_meta, DROP image_crop, DROP contact_image, DROP contact_image_meta, DROP contact_image_crop, DROP contact_lifestyle_image, DROP contact_lifestyle_image_meta, DROP contact_lifestyle_image_crop, DROP description, DROP tenants, DROP contacts, DROP location_latitude, DROP location_longitude, DROP intersection, CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE leasing_brochure_plan DROP INDEX IDX_14A2DC65B96114D1, ADD UNIQUE INDEX UNIQ_14A2DC65B96114D1 (brochure_id)');
    }
}
