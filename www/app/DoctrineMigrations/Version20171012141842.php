<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171012141842 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68BD0F409C');
        $this->addSql('DROP INDEX IDX_D7943D68BD0F409C ON area');
        $this->addSql('ALTER TABLE area CHANGE area_id parent_area_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68CF4734DA FOREIGN KEY (parent_area_id) REFERENCES area (id)');
        $this->addSql('CREATE INDEX IDX_D7943D68CF4734DA ON area (parent_area_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68CF4734DA');
        $this->addSql('DROP INDEX IDX_D7943D68CF4734DA ON area');
        $this->addSql('ALTER TABLE area CHANGE parent_area_id area_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('CREATE INDEX IDX_D7943D68BD0F409C ON area (area_id)');
    }
}
