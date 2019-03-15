<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170827125839 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE careers_position ADD lang_id INT DEFAULT NULL, ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE careers_position ADD CONSTRAINT FK_9A0E8904B213FA4 FOREIGN KEY (lang_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE careers_position ADD CONSTRAINT FK_9A0E8904F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9A0E8904B213FA4 ON careers_position (lang_id)');
        $this->addSql('CREATE INDEX IDX_9A0E8904F6BD1646 ON careers_position (site_id)');

        $this->addSql('UPDATE careers_position SET lang_id=1, site_id=1');
        $this->addSql('UPDATE community_retail_art SET sort_order=0 where sort_order is null');
        $this->addSql('UPDATE community_retail_art SET site_id=1');
        $this->addSql('UPDATE careers_people SET site_id=1');
        $this->addSql('UPDATE faq SET sort_order=0 where sort_order is null');
        $this->addSql('UPDATE faq SET site_id=1');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE careers_position DROP FOREIGN KEY FK_9A0E8904B213FA4');
        $this->addSql('ALTER TABLE careers_position DROP FOREIGN KEY FK_9A0E8904F6BD1646');
        $this->addSql('DROP INDEX IDX_9A0E8904B213FA4 ON careers_position');
        $this->addSql('DROP INDEX IDX_9A0E8904F6BD1646 ON careers_position');
        $this->addSql('ALTER TABLE careers_position DROP lang_id, DROP site_id');
    }
}
