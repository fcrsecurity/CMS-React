<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170705172424 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE investors_dividend ADD lang_id INT NOT NULL DEFAULT 1, ADD copy_of_id INT DEFAULT NULL, ADD lang_parent_id INT DEFAULT NULL, ADD created_by_id INT NOT NULL DEFAULT 1, ADD updated_by_id INT DEFAULT NULL, ADD sort_order INT DEFAULT NULL, ADD access LONGTEXT DEFAULT NULL, ADD version VARCHAR(20) DEFAULT NULL, ADD version_comment LONGTEXT DEFAULT NULL, ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE investors_dividend ADD CONSTRAINT FK_5F2D6F66B213FA4 FOREIGN KEY (lang_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE investors_dividend ADD CONSTRAINT FK_5F2D6F66EED7735B FOREIGN KEY (copy_of_id) REFERENCES investors_dividend (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investors_dividend ADD CONSTRAINT FK_5F2D6F66C0165BAE FOREIGN KEY (lang_parent_id) REFERENCES investors_dividend (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investors_dividend ADD CONSTRAINT FK_5F2D6F66B03A8386 FOREIGN KEY (created_by_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investors_dividend ADD CONSTRAINT FK_5F2D6F66896DBBDE FOREIGN KEY (updated_by_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5F2D6F66B213FA4 ON investors_dividend (lang_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2D6F66EED7735B ON investors_dividend (copy_of_id)');
        $this->addSql('CREATE INDEX IDX_5F2D6F66C0165BAE ON investors_dividend (lang_parent_id)');
        $this->addSql('CREATE INDEX IDX_5F2D6F66B03A8386 ON investors_dividend (created_by_id)');
        $this->addSql('CREATE INDEX IDX_5F2D6F66896DBBDE ON investors_dividend (updated_by_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE investors_dividend DROP FOREIGN KEY FK_5F2D6F66B213FA4');
        $this->addSql('ALTER TABLE investors_dividend DROP FOREIGN KEY FK_5F2D6F66EED7735B');
        $this->addSql('ALTER TABLE investors_dividend DROP FOREIGN KEY FK_5F2D6F66C0165BAE');
        $this->addSql('ALTER TABLE investors_dividend DROP FOREIGN KEY FK_5F2D6F66B03A8386');
        $this->addSql('ALTER TABLE investors_dividend DROP FOREIGN KEY FK_5F2D6F66896DBBDE');
        $this->addSql('DROP INDEX IDX_5F2D6F66B213FA4 ON investors_dividend');
        $this->addSql('DROP INDEX UNIQ_5F2D6F66EED7735B ON investors_dividend');
        $this->addSql('DROP INDEX IDX_5F2D6F66C0165BAE ON investors_dividend');
        $this->addSql('DROP INDEX IDX_5F2D6F66B03A8386 ON investors_dividend');
        $this->addSql('DROP INDEX IDX_5F2D6F66896DBBDE ON investors_dividend');
        $this->addSql('ALTER TABLE investors_dividend DROP lang_id, DROP copy_of_id, DROP lang_parent_id, DROP created_by_id, DROP updated_by_id, DROP sort_order, DROP access, DROP version, DROP version_comment, DROP created, DROP updated, DROP deleted_at');
    }
}
