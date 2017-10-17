<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171017135848 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE legal DROP FOREIGN KEY FK_E362C0508BAC62AF');
        $this->addSql('DROP INDEX IDX_E362C0508BAC62AF ON legal');
        $this->addSql('ALTER TABLE legal ADD city VARCHAR(255) NOT NULL, DROP city_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE legal ADD city_id INT DEFAULT NULL, DROP city');
        $this->addSql('ALTER TABLE legal ADD CONSTRAINT FK_E362C0508BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_E362C0508BAC62AF ON legal (city_id)');
    }
}
