<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171130140738 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM leasing_brochure');
        $this->addSql('ALTER TABLE leasing_brochure ADD lang_id INT NOT NULL DEFAULT 1, ADD site_id INT DEFAULT NULL, ADD created_by_id INT NOT NULL DEFAULT 1, ADD updated_by_id INT DEFAULT NULL, ADD sort_order INT DEFAULT NULL, ADD access LONGTEXT DEFAULT NULL, ADD version VARCHAR(20) DEFAULT NULL, ADD version_comment LONGTEXT DEFAULT NULL, ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74CB213FA4 FOREIGN KEY (lang_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74CF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74CB03A8386 FOREIGN KEY (created_by_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74C896DBBDE FOREIGN KEY (updated_by_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7E74C74CB213FA4 ON leasing_brochure (lang_id)');
        $this->addSql('CREATE INDEX IDX_7E74C74CF6BD1646 ON leasing_brochure (site_id)');
        $this->addSql('CREATE INDEX IDX_7E74C74CB03A8386 ON leasing_brochure (created_by_id)');
        $this->addSql('CREATE INDEX IDX_7E74C74C896DBBDE ON leasing_brochure (updated_by_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74CB213FA4');
        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74CF6BD1646');
        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74CB03A8386');
        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74C896DBBDE');
        $this->addSql('DROP INDEX IDX_7E74C74CB213FA4 ON leasing_brochure');
        $this->addSql('DROP INDEX IDX_7E74C74CF6BD1646 ON leasing_brochure');
        $this->addSql('DROP INDEX IDX_7E74C74CB03A8386 ON leasing_brochure');
        $this->addSql('DROP INDEX IDX_7E74C74C896DBBDE ON leasing_brochure');
        $this->addSql('ALTER TABLE leasing_brochure DROP lang_id, DROP site_id, DROP created_by_id, DROP updated_by_id, DROP sort_order, DROP access, DROP version, DROP version_comment, DROP created, DROP updated, DROP deleted_at');
    }
}
