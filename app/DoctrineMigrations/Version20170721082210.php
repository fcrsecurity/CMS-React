<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170721082210 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE investors_press_release ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investors_press_release ADD CONSTRAINT FK_2764F642F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2764F642F6BD1646 ON investors_press_release (site_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE investors_press_release DROP FOREIGN KEY FK_2764F642F6BD1646');
        $this->addSql('DROP INDEX IDX_2764F642F6BD1646 ON investors_press_release');
        $this->addSql('ALTER TABLE investors_press_release DROP site_id');
    }
}
