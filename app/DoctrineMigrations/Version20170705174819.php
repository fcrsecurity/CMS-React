<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170705174819 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE investors_debenture ADD lang_id INT NOT NULL DEFAULT 1, ADD copy_of_id INT DEFAULT NULL, ADD lang_parent_id INT DEFAULT NULL, ADD created_by_id INT NOT NULL DEFAULT 1, ADD updated_by_id INT DEFAULT NULL, ADD sort_order INT DEFAULT NULL, ADD access LONGTEXT DEFAULT NULL, ADD version VARCHAR(20) DEFAULT NULL, ADD version_comment LONGTEXT DEFAULT NULL, ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE investors_debenture ADD CONSTRAINT FK_56562FACB213FA4 FOREIGN KEY (lang_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE investors_debenture ADD CONSTRAINT FK_56562FACEED7735B FOREIGN KEY (copy_of_id) REFERENCES investors_debenture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investors_debenture ADD CONSTRAINT FK_56562FACC0165BAE FOREIGN KEY (lang_parent_id) REFERENCES investors_debenture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investors_debenture ADD CONSTRAINT FK_56562FACB03A8386 FOREIGN KEY (created_by_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE investors_debenture ADD CONSTRAINT FK_56562FAC896DBBDE FOREIGN KEY (updated_by_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_56562FACB213FA4 ON investors_debenture (lang_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56562FACEED7735B ON investors_debenture (copy_of_id)');
        $this->addSql('CREATE INDEX IDX_56562FACC0165BAE ON investors_debenture (lang_parent_id)');
        $this->addSql('CREATE INDEX IDX_56562FACB03A8386 ON investors_debenture (created_by_id)');
        $this->addSql('CREATE INDEX IDX_56562FAC896DBBDE ON investors_debenture (updated_by_id)');
        $this->addSql('ALTER TABLE investors_dividend CHANGE created_by_id created_by_id INT NOT NULL, CHANGE lang_id lang_id INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE investors_debenture DROP FOREIGN KEY FK_56562FACB213FA4');
        $this->addSql('ALTER TABLE investors_debenture DROP FOREIGN KEY FK_56562FACEED7735B');
        $this->addSql('ALTER TABLE investors_debenture DROP FOREIGN KEY FK_56562FACC0165BAE');
        $this->addSql('ALTER TABLE investors_debenture DROP FOREIGN KEY FK_56562FACB03A8386');
        $this->addSql('ALTER TABLE investors_debenture DROP FOREIGN KEY FK_56562FAC896DBBDE');
        $this->addSql('DROP INDEX IDX_56562FACB213FA4 ON investors_debenture');
        $this->addSql('DROP INDEX UNIQ_56562FACEED7735B ON investors_debenture');
        $this->addSql('DROP INDEX IDX_56562FACC0165BAE ON investors_debenture');
        $this->addSql('DROP INDEX IDX_56562FACB03A8386 ON investors_debenture');
        $this->addSql('DROP INDEX IDX_56562FAC896DBBDE ON investors_debenture');
        $this->addSql('ALTER TABLE investors_debenture DROP lang_id, DROP copy_of_id, DROP lang_parent_id, DROP created_by_id, DROP updated_by_id, DROP sort_order, DROP access, DROP version, DROP version_comment, DROP created, DROP updated, DROP deleted_at');
        $this->addSql('ALTER TABLE investors_dividend CHANGE lang_id lang_id INT DEFAULT 1 NOT NULL, CHANGE created_by_id created_by_id INT DEFAULT 1 NOT NULL');
    }
}
