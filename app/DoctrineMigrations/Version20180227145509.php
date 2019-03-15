<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180227145509 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_brochure CHANGE pdf pdf VARCHAR(2048) DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_brochure_images CHANGE image image VARCHAR(2048) DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_brochure_plan CHANGE image image VARCHAR(2048) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_brochure CHANGE pdf pdf VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE leasing_brochure_images CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE leasing_brochure_plan CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
