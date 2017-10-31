<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171031144210 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE legal ADD comment VARCHAR(255) DEFAULT NULL, CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE building_number building_number VARCHAR(255) DEFAULT NULL, CHANGE building_subnumber building_subnumber VARCHAR(255) DEFAULT NULL, CHANGE building_part building_part VARCHAR(255) DEFAULT NULL, CHANGE appartment appartment VARCHAR(255) DEFAULT NULL, CHANGE delivery_street delivery_street VARCHAR(255) DEFAULT NULL, CHANGE delivery_building_number delivery_building_number VARCHAR(255) DEFAULT NULL, CHANGE delivery_building_subnumber delivery_building_subnumber VARCHAR(255) DEFAULT NULL, CHANGE delivery_building_part delivery_building_part VARCHAR(255) DEFAULT NULL, CHANGE delivery_appartment delivery_appartment VARCHAR(255) DEFAULT NULL, CHANGE contact_fax contact_fax VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE legal DROP comment, CHANGE street street VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE building_number building_number VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE building_subnumber building_subnumber VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE building_part building_part VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE appartment appartment VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE contact_fax contact_fax VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE delivery_street delivery_street VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE delivery_building_number delivery_building_number VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE delivery_building_subnumber delivery_building_subnumber VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE delivery_building_part delivery_building_part VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE delivery_appartment delivery_appartment VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
