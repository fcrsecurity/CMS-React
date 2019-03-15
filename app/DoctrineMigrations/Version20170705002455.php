<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170705002455 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_tenant ADD lang_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_tenant ADD CONSTRAINT FK_F9033AAAC0165BAE FOREIGN KEY (lang_parent_id) REFERENCES leasing_tenant (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F9033AAAC0165BAE ON leasing_tenant (lang_parent_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_tenant DROP FOREIGN KEY FK_F9033AAAC0165BAE');
        $this->addSql('DROP INDEX IDX_F9033AAAC0165BAE ON leasing_tenant');
        $this->addSql('ALTER TABLE leasing_tenant DROP lang_parent_id');
    }
}
