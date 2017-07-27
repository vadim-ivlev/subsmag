<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170727131045 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, good_id INT DEFAULT NULL, tariff_id INT DEFAULT NULL, quantity INT NOT NULL, cost DOUBLE PRECISION NOT NULL, INDEX IDX_1F1B251E8D9F6D38 (order_id), INDEX IDX_1F1B251E1CF98C70 (good_id), INDEX IDX_1F1B251E92348FD2 (tariff_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeunit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, bitmask SMALLINT NOT NULL, year SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE good (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, period_id INT DEFAULT NULL, area_id INT DEFAULT NULL, start DATE NOT NULL, end DATE NOT NULL, is_regional TINYINT(1) NOT NULL, INDEX IDX_6C844E924584665A (product_id), INDEX IDX_6C844E92EC8B7ADE (period_id), INDEX IDX_6C844E92BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, `alias` VARCHAR(50) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medium (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, `alias` VARCHAR(50) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(200) NOT NULL, password VARCHAR(200) NOT NULL, `key` VARCHAR(200) NOT NULL, can_rest TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, first_month SMALLINT NOT NULL, duration SMALLINT NOT NULL, year SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, payment_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, address TEXT NOT NULL, sum DOUBLE PRECISION NOT NULL, is_paid TINYINT(1) NOT NULL, INDEX IDX_F52993984C3A3BB (payment_id), INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, `text` LONGTEXT NOT NULL, postal_index VARCHAR(10) NOT NULL, is_active TINYINT(1) NOT NULL, is_archive TINYINT(1) NOT NULL, outer_link LONGTEXT NOT NULL, is_kit TINYINT(1) NOT NULL, sort SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_edition (product_id INT NOT NULL, edition_id INT NOT NULL, INDEX IDX_794803AF4584665A (product_id), INDEX IDX_794803AF74281A5E (edition_id), PRIMARY KEY(product_id, edition_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tariff (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, timeunit_id INT DEFAULT NULL, delivery_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, medium_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_9465207D4584665A (product_id), INDEX IDX_9465207D5878D878 (timeunit_id), INDEX IDX_9465207D12136921 (delivery_id), INDEX IDX_9465207D9F2C3FAB (zone_id), INDEX IDX_9465207DE252B6A5 (medium_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, keyword VARCHAR(32) NOT NULL, description LONGTEXT NOT NULL, `text` LONGTEXT NOT NULL, frequency SMALLINT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, zone_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, from_front_id INT NOT NULL, INDEX IDX_D7943D689F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1CF98C70 FOREIGN KEY (good_id) REFERENCES good (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E92348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E924584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E92EC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E92BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_edition ADD CONSTRAINT FK_794803AF4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_edition ADD CONSTRAINT FK_794803AF74281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D5878D878 FOREIGN KEY (timeunit_id) REFERENCES timeunit (id)');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207DE252B6A5 FOREIGN KEY (medium_id) REFERENCES medium (id)');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D689F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D5878D878');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1CF98C70');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D12136921');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207DE252B6A5');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE good DROP FOREIGN KEY FK_6C844E92EC8B7ADE');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D9F2C3FAB');
        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D689F2C3FAB');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E8D9F6D38');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984C3A3BB');
        $this->addSql('ALTER TABLE good DROP FOREIGN KEY FK_6C844E924584665A');
        $this->addSql('ALTER TABLE product_edition DROP FOREIGN KEY FK_794803AF4584665A');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D4584665A');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E92348FD2');
        $this->addSql('ALTER TABLE product_edition DROP FOREIGN KEY FK_794803AF74281A5E');
        $this->addSql('ALTER TABLE good DROP FOREIGN KEY FK_6C844E92BD0F409C');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE timeunit');
        $this->addSql('DROP TABLE good');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE medium');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE period');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_edition');
        $this->addSql('DROP TABLE tariff');
        $this->addSql('DROP TABLE edition');
        $this->addSql('DROP TABLE area');
    }
}
