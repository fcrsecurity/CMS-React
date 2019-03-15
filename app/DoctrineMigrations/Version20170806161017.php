<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170806161017 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE aoda_long_description (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, language_id INT DEFAULT NULL, object_class LONGTEXT NOT NULL, object_id INT NOT NULL, field_name VARCHAR(25) NOT NULL, long_description LONGTEXT NOT NULL, access LONGTEXT DEFAULT NULL, sort_order INT DEFAULT NULL, INDEX site_id (site_id), INDEX language_id (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aoda_long_description ADD CONSTRAINT FK_1C1B621AF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE aoda_long_description ADD CONSTRAINT FK_1C1B621A82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE aoda_long_description');
    }
}
