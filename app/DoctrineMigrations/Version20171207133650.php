<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171207133650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //cleanup
        $this->addSql('DELETE FROM leasing_brochure_plan');
        $this->addSql('DELETE FROM leasing_brochure_demographic');
        $this->addSql('DELETE FROM leasing_brochure');

        $this->addSql('ALTER TABLE leasing_brochure ADD lang_parent_id INT DEFAULT NULL, ADD property_id INT NOT NULL');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74CC0165BAE FOREIGN KEY (lang_parent_id) REFERENCES leasing_brochure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leasing_brochure ADD CONSTRAINT FK_7E74C74C549213EC FOREIGN KEY (property_id) REFERENCES leasing_property (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7E74C74CC0165BAE ON leasing_brochure (lang_parent_id)');
        $this->addSql('CREATE INDEX IDX_7E74C74C549213EC ON leasing_brochure (property_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74CC0165BAE');
        $this->addSql('ALTER TABLE leasing_brochure DROP FOREIGN KEY FK_7E74C74C549213EC');
        $this->addSql('DROP INDEX IDX_7E74C74CC0165BAE ON leasing_brochure');
        $this->addSql('DROP INDEX IDX_7E74C74C549213EC ON leasing_brochure');
        $this->addSql('ALTER TABLE leasing_brochure DROP lang_parent_id, DROP property_id');
    }
}
