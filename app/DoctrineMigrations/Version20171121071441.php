<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171121071441 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE filemanager_object (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, owner_id INT NOT NULL, name VARCHAR(1024) NOT NULL, path VARCHAR(2048) NOT NULL, meta VARCHAR(2048) DEFAULT NULL, INDEX IDX_CC161935727ACA70 (parent_id), INDEX IDX_CC1619357E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filemanager_object ADD CONSTRAINT FK_CC161935727ACA70 FOREIGN KEY (parent_id) REFERENCES filemanager_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filemanager_object ADD CONSTRAINT FK_CC1619357E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE filemanager_object DROP FOREIGN KEY FK_CC161935727ACA70');
        $this->addSql('DROP TABLE filemanager_object');
    }
}
