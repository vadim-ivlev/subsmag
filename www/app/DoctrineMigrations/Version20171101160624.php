<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171101160624 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` ADD city_id INT DEFAULT NULL, ADD postcode VARCHAR(255) NOT NULL, ADD street VARCHAR(255) DEFAULT NULL, ADD building_number VARCHAR(255) DEFAULT NULL, ADD building_subnumber VARCHAR(255) DEFAULT NULL, ADD building_part VARCHAR(255) DEFAULT NULL, ADD appartment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993988BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_F52993988BAC62AF ON `order` (city_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993988BAC62AF');
        $this->addSql('DROP INDEX IDX_F52993988BAC62AF ON `order`');
        $this->addSql('ALTER TABLE `order` DROP city_id, DROP postcode, DROP street, DROP building_number, DROP building_subnumber, DROP building_part, DROP appartment');
    }
}
