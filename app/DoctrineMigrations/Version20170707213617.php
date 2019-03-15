<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170707213617 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_property ADD site_id INT NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE leasing_property ADD CONSTRAINT FK_D2D429D9F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D2D429D9F6BD1646 ON leasing_property (site_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_property DROP FOREIGN KEY FK_D2D429D9F6BD1646');
        $this->addSql('DROP INDEX IDX_D2D429D9F6BD1646 ON leasing_property');
        $this->addSql('ALTER TABLE leasing_property DROP site_id');
    }
}
