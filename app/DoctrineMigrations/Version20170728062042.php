<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170728062042 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_property_vacancy DROP name');
        $this->addSql('ALTER TABLE leasing_property_filter CHANGE is_filter_grocery_anchored is_filter_grocery_anchored TINYINT(1) DEFAULT NULL, CHANGE is_filter_urban_retail is_filter_urban_retail TINYINT(1) DEFAULT NULL, CHANGE is_filter_office_space is_filter_office_space TINYINT(1) DEFAULT NULL, CHANGE is_filter_under_development is_filter_under_development TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_property_demographic CHANGE annual_average_daily_traffic annual_average_daily_traffic DOUBLE PRECISION DEFAULT NULL, CHANGE population_1km population_1km DOUBLE PRECISION DEFAULT NULL, CHANGE household_1km household_1km DOUBLE PRECISION DEFAULT NULL, CHANGE household_income_1km household_income_1km DOUBLE PRECISION DEFAULT NULL, CHANGE population_3km population_3km DOUBLE PRECISION DEFAULT NULL, CHANGE household_3km household_3km DOUBLE PRECISION DEFAULT NULL, CHANGE household_income_3km household_income_3km DOUBLE PRECISION DEFAULT NULL, CHANGE population_5km population_5km DOUBLE PRECISION DEFAULT NULL, CHANGE household_5km household_5km DOUBLE PRECISION DEFAULT NULL, CHANGE household_income_5km household_income_5km DOUBLE PRECISION DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_property_demographic CHANGE annual_average_daily_traffic annual_average_daily_traffic DOUBLE PRECISION NOT NULL, CHANGE population_1km population_1km DOUBLE PRECISION NOT NULL, CHANGE household_1km household_1km DOUBLE PRECISION NOT NULL, CHANGE household_income_1km household_income_1km DOUBLE PRECISION NOT NULL, CHANGE population_3km population_3km DOUBLE PRECISION NOT NULL, CHANGE household_3km household_3km DOUBLE PRECISION NOT NULL, CHANGE household_income_3km household_income_3km DOUBLE PRECISION NOT NULL, CHANGE population_5km population_5km DOUBLE PRECISION NOT NULL, CHANGE household_5km household_5km DOUBLE PRECISION NOT NULL, CHANGE household_income_5km household_income_5km DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE leasing_property_filter CHANGE is_filter_grocery_anchored is_filter_grocery_anchored TINYINT(1) NOT NULL, CHANGE is_filter_urban_retail is_filter_urban_retail TINYINT(1) NOT NULL, CHANGE is_filter_office_space is_filter_office_space TINYINT(1) NOT NULL, CHANGE is_filter_under_development is_filter_under_development TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE leasing_property_vacancy ADD name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
