<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171011151914 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, area_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_2D5B0234BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legal (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, delivery_city_id INT DEFAULT NULL, name LONGTEXT NOT NULL, inn VARCHAR(255) NOT NULL, kpp VARCHAR(255) NOT NULL, bank_name VARCHAR(255) NOT NULL, bank_account VARCHAR(255) NOT NULL, bik VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, building_number VARCHAR(255) NOT NULL, building_subnumber VARCHAR(255) NOT NULL, building_part VARCHAR(255) NOT NULL, appartment VARCHAR(255) NOT NULL, contact_name VARCHAR(255) NOT NULL, contact_phone VARCHAR(255) NOT NULL, contact_email VARCHAR(255) NOT NULL, delivery_postcode VARCHAR(255) NOT NULL, delivery_street VARCHAR(255) NOT NULL, delivery_building_number VARCHAR(255) NOT NULL, delivery_building_subnumber VARCHAR(255) NOT NULL, delivery_building_part VARCHAR(255) NOT NULL, delivery_appartment VARCHAR(255) NOT NULL, INDEX IDX_E362C0508BAC62AF (city_id), INDEX IDX_E362C05081B2DD71 (delivery_city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE legal ADD CONSTRAINT FK_E362C0508BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE legal ADD CONSTRAINT FK_E362C05081B2DD71 FOREIGN KEY (delivery_city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE `order` ADD legal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939862BB3C59 FOREIGN KEY (legal_id) REFERENCES legal (id)');
        $this->addSql('CREATE INDEX IDX_F529939862BB3C59 ON `order` (legal_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE legal DROP FOREIGN KEY FK_E362C0508BAC62AF');
        $this->addSql('ALTER TABLE legal DROP FOREIGN KEY FK_E362C05081B2DD71');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939862BB3C59');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE legal');
        $this->addSql('DROP INDEX IDX_F529939862BB3C59 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP legal_id');
    }
}
