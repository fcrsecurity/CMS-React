<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801004049 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_manager ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_manager ADD CONSTRAINT FK_6F2C35C5F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6F2C35C5F6BD1646 ON leasing_manager (site_id)');
        $this->addSql('ALTER TABLE investors_analyst_coverage ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investors_analyst_coverage ADD CONSTRAINT FK_884972AEF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_884972AEF6BD1646 ON investors_analyst_coverage (site_id)');
        $this->addSql('ALTER TABLE leasing_active_province ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_active_province ADD CONSTRAINT FK_D9C297C8F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D9C297C8F6BD1646 ON leasing_active_province (site_id)');
        $this->addSql('ALTER TABLE investors_financial_report ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investors_financial_report ADD CONSTRAINT FK_A9C5666F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A9C5666F6BD1646 ON investors_financial_report (site_id)');
        $this->addSql('ALTER TABLE investors_debenture ADD site_id INT DEFAULT NULL, CHANGE created_by_id created_by_id INT NOT NULL, CHANGE lang_id lang_id INT NOT NULL');
        $this->addSql('ALTER TABLE investors_debenture ADD CONSTRAINT FK_56562FACF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_56562FACF6BD1646 ON investors_debenture (site_id)');
        $this->addSql('ALTER TABLE office ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B02F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_74516B02F6BD1646 ON office (site_id)');
        $this->addSql('ALTER TABLE investors_dividend ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investors_dividend ADD CONSTRAINT FK_5F2D6F66F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5F2D6F66F6BD1646 ON investors_dividend (site_id)');
        $this->addSql('ALTER TABLE careers_slider ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE careers_slider ADD CONSTRAINT FK_7B4C6F11F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7B4C6F11F6BD1646 ON careers_slider (site_id)');
        $this->addSql('ALTER TABLE leasing_tenant ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE leasing_tenant ADD CONSTRAINT FK_F9033AAAF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F9033AAAF6BD1646 ON leasing_tenant (site_id)');
        $this->addSql('ALTER TABLE careers_employee ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE careers_employee ADD CONSTRAINT FK_81BD1850F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_81BD1850F6BD1646 ON careers_employee (site_id)');
        $this->addSql('ALTER TABLE investor_conference_call ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investor_conference_call ADD CONSTRAINT FK_AC14A8E8F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_AC14A8E8F6BD1646 ON investor_conference_call (site_id)');
        $this->addSql('ALTER TABLE community_retail_art ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE community_retail_art ADD CONSTRAINT FK_D0F18373F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D0F18373F6BD1646 ON community_retail_art (site_id)');
        $this->addSql('ALTER TABLE property_feature_slider ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property_feature_slider ADD CONSTRAINT FK_60162DADF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_60162DADF6BD1646 ON property_feature_slider (site_id)');
        $this->addSql('ALTER TABLE faq ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE faq ADD CONSTRAINT FK_E8FF75CCF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_E8FF75CCF6BD1646 ON faq (site_id)');
        $this->addSql('ALTER TABLE careers_people ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE careers_people ADD CONSTRAINT FK_9C9D1530F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9C9D1530F6BD1646 ON careers_people (site_id)');
        $this->addSql('ALTER TABLE sustainability ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sustainability ADD CONSTRAINT FK_5E6689C1F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5E6689C1F6BD1646 ON sustainability (site_id)');
        $this->addSql('ALTER TABLE sustainability_slider ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sustainability_slider ADD CONSTRAINT FK_5881DCD7F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5881DCD7F6BD1646 ON sustainability_slider (site_id)');
        $this->addSql('ALTER TABLE menu ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7D053A93F6BD1646 ON menu (site_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE careers_employee DROP FOREIGN KEY FK_81BD1850F6BD1646');
        $this->addSql('DROP INDEX IDX_81BD1850F6BD1646 ON careers_employee');
        $this->addSql('ALTER TABLE careers_employee DROP site_id');
        $this->addSql('ALTER TABLE careers_people DROP FOREIGN KEY FK_9C9D1530F6BD1646');
        $this->addSql('DROP INDEX IDX_9C9D1530F6BD1646 ON careers_people');
        $this->addSql('ALTER TABLE careers_people DROP site_id');
        $this->addSql('ALTER TABLE careers_slider DROP FOREIGN KEY FK_7B4C6F11F6BD1646');
        $this->addSql('DROP INDEX IDX_7B4C6F11F6BD1646 ON careers_slider');
        $this->addSql('ALTER TABLE careers_slider DROP site_id');
        $this->addSql('ALTER TABLE community_retail_art DROP FOREIGN KEY FK_D0F18373F6BD1646');
        $this->addSql('DROP INDEX IDX_D0F18373F6BD1646 ON community_retail_art');
        $this->addSql('ALTER TABLE community_retail_art DROP site_id');
        $this->addSql('ALTER TABLE faq DROP FOREIGN KEY FK_E8FF75CCF6BD1646');
        $this->addSql('DROP INDEX IDX_E8FF75CCF6BD1646 ON faq');
        $this->addSql('ALTER TABLE faq DROP site_id');
        $this->addSql('ALTER TABLE investor_conference_call DROP FOREIGN KEY FK_AC14A8E8F6BD1646');
        $this->addSql('DROP INDEX IDX_AC14A8E8F6BD1646 ON investor_conference_call');
        $this->addSql('ALTER TABLE investor_conference_call DROP site_id');
        $this->addSql('ALTER TABLE investors_analyst_coverage DROP FOREIGN KEY FK_884972AEF6BD1646');
        $this->addSql('DROP INDEX IDX_884972AEF6BD1646 ON investors_analyst_coverage');
        $this->addSql('ALTER TABLE investors_analyst_coverage DROP site_id');
        $this->addSql('ALTER TABLE investors_debenture DROP FOREIGN KEY FK_56562FACF6BD1646');
        $this->addSql('DROP INDEX IDX_56562FACF6BD1646 ON investors_debenture');
        $this->addSql('ALTER TABLE investors_debenture DROP site_id, CHANGE lang_id lang_id INT DEFAULT 1 NOT NULL, CHANGE created_by_id created_by_id INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE investors_dividend DROP FOREIGN KEY FK_5F2D6F66F6BD1646');
        $this->addSql('DROP INDEX IDX_5F2D6F66F6BD1646 ON investors_dividend');
        $this->addSql('ALTER TABLE investors_dividend DROP site_id');
        $this->addSql('ALTER TABLE investors_financial_report DROP FOREIGN KEY FK_A9C5666F6BD1646');
        $this->addSql('DROP INDEX IDX_A9C5666F6BD1646 ON investors_financial_report');
        $this->addSql('ALTER TABLE investors_financial_report DROP site_id');
        $this->addSql('ALTER TABLE leasing_active_province DROP FOREIGN KEY FK_D9C297C8F6BD1646');
        $this->addSql('DROP INDEX IDX_D9C297C8F6BD1646 ON leasing_active_province');
        $this->addSql('ALTER TABLE leasing_active_province DROP site_id');
        $this->addSql('ALTER TABLE leasing_manager DROP FOREIGN KEY FK_6F2C35C5F6BD1646');
        $this->addSql('DROP INDEX IDX_6F2C35C5F6BD1646 ON leasing_manager');
        $this->addSql('ALTER TABLE leasing_manager DROP site_id');
        $this->addSql('ALTER TABLE leasing_tenant DROP FOREIGN KEY FK_F9033AAAF6BD1646');
        $this->addSql('DROP INDEX IDX_F9033AAAF6BD1646 ON leasing_tenant');
        $this->addSql('ALTER TABLE leasing_tenant DROP site_id');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93F6BD1646');
        $this->addSql('DROP INDEX IDX_7D053A93F6BD1646 ON menu');
        $this->addSql('ALTER TABLE menu DROP site_id');
        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B02F6BD1646');
        $this->addSql('DROP INDEX IDX_74516B02F6BD1646 ON office');
        $this->addSql('ALTER TABLE office DROP site_id');
        $this->addSql('ALTER TABLE property_feature_slider DROP FOREIGN KEY FK_60162DADF6BD1646');
        $this->addSql('DROP INDEX IDX_60162DADF6BD1646 ON property_feature_slider');
        $this->addSql('ALTER TABLE property_feature_slider DROP site_id');
        $this->addSql('ALTER TABLE sustainability DROP FOREIGN KEY FK_5E6689C1F6BD1646');
        $this->addSql('DROP INDEX IDX_5E6689C1F6BD1646 ON sustainability');
        $this->addSql('ALTER TABLE sustainability DROP site_id');
        $this->addSql('ALTER TABLE sustainability_slider DROP FOREIGN KEY FK_5881DCD7F6BD1646');
        $this->addSql('DROP INDEX IDX_5881DCD7F6BD1646 ON sustainability_slider');
        $this->addSql('ALTER TABLE sustainability_slider DROP site_id');
    }
}
