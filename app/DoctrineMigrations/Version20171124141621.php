<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171124141621 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE leasing_brochure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status ENUM(\'draft\', \'waiting_for_approval\', \'approved\'), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leasing_brochure_aerial (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, annual_average_daily_traffic DOUBLE PRECISION DEFAULT NULL, population_1km DOUBLE PRECISION DEFAULT NULL, household_1km DOUBLE PRECISION DEFAULT NULL, household_income_1km DOUBLE PRECISION DEFAULT NULL, population_3km DOUBLE PRECISION DEFAULT NULL, household_3km DOUBLE PRECISION DEFAULT NULL, household_income_3km DOUBLE PRECISION DEFAULT NULL, population_5km DOUBLE PRECISION DEFAULT NULL, household_5km DOUBLE PRECISION DEFAULT NULL, household_income_5km DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_6CAD2D6EB96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leasing_brochure_contact (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, image LONGTEXT DEFAULT NULL, lifestyle_image VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address1 VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, leasing_first_name VARCHAR(255) DEFAULT NULL, leasing_last_name VARCHAR(255) DEFAULT NULL, leasing_type VARCHAR(255) DEFAULT NULL, leasing_title VARCHAR(255) DEFAULT NULL, leasing_email VARCHAR(255) DEFAULT NULL, leasing_fax VARCHAR(255) DEFAULT NULL, leasing_phone VARCHAR(255) DEFAULT NULL, leasing_phone_extension VARCHAR(255) DEFAULT NULL, location_name VARCHAR(255) DEFAULT NULL, location_address1 VARCHAR(255) DEFAULT NULL, location_address2 VARCHAR(255) DEFAULT NULL, location_latitude VARCHAR(255) DEFAULT NULL, location_longitude VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_99F3609BB96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leasing_brochure_cover (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, province VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, tagline VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, tenants LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_9EADE614B96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leasing_brochure_plan (id INT AUTO_INCREMENT NOT NULL, brochure_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_14A2DC65B96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leasing_brochure_aerial ADD CONSTRAINT FK_6CAD2D6EB96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure_contact ADD CONSTRAINT FK_99F3609BB96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure_cover ADD CONSTRAINT FK_9EADE614B96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure_plan ADD CONSTRAINT FK_14A2DC65B96114D1 FOREIGN KEY (brochure_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_brochure_aerial DROP FOREIGN KEY FK_6CAD2D6EB96114D1');
        $this->addSql('ALTER TABLE leasing_brochure_contact DROP FOREIGN KEY FK_99F3609BB96114D1');
        $this->addSql('ALTER TABLE leasing_brochure_cover DROP FOREIGN KEY FK_9EADE614B96114D1');
        $this->addSql('ALTER TABLE leasing_brochure_plan DROP FOREIGN KEY FK_14A2DC65B96114D1');
        $this->addSql('DROP TABLE leasing_brochure');
        $this->addSql('DROP TABLE leasing_brochure_aerial');
        $this->addSql('DROP TABLE leasing_brochure_contact');
        $this->addSql('DROP TABLE leasing_brochure_cover');
        $this->addSql('DROP TABLE leasing_brochure_plan');
    }
}
