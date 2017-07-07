<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170707121109 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name_product VARCHAR(128) NOT NULL, frequency SMALLINT NOT NULL, flag_subscribe TINYINT(1) NOT NULL, flag_buy TINYINT(1) NOT NULL, post_index INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(128) NOT NULL, password VARCHAR(255) NOT NULL, user_key VARCHAR(255) NOT NULL, date_registration DATETIME NOT NULL, date_lastlogin DATETIME NOT NULL, flag_can_rest TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscribes (id INT AUTO_INCREMENT NOT NULL, name_period_id INT NOT NULL, area_id INT NOT NULL, subscribe_period_start DATETIME NOT NULL, subscribe_period_end DATETIME NOT NULL, product_id INT NOT NULL, kit_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE areas (id INT AUTO_INCREMENT NOT NULL, name_area VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kits (id INT AUTO_INCREMENT NOT NULL, name_kit VARCHAR(255) NOT NULL, flag_subscribe TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, kit_id INT NOT NULL, zone_id INT NOT NULL, subscribe_id INT NOT NULL, date DATETIME NOT NULL, address TINYTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, flag_paid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promocodes (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, flag_used TINYINT(1) NOT NULL, action_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, introtext VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, discount SMALLINT NOT NULL, gift_description LONGTEXT NOT NULL, flag_visible_on_site TINYINT(1) NOT NULL, flag_percent_or_fix SMALLINT NOT NULL, cnt_used SMALLINT NOT NULL, product_id INT NOT NULL, kit_id INT NOT NULL, promocode_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zones (id INT AUTO_INCREMENT NOT NULL, zone_number SMALLINT NOT NULL, area_id SMALLINT NOT NULL, tarif_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periods (id INT AUTO_INCREMENT NOT NULL, month_start SMALLINT NOT NULL, year_start SMALLINT NOT NULL, period_months SMALLINT NOT NULL, quantity_months_start SMALLINT NOT NULL, quantity_months_end SMALLINT NOT NULL, subscribe_id SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE subscribes');
        $this->addSql('DROP TABLE areas');
        $this->addSql('DROP TABLE kits');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE promocodes');
        $this->addSql('DROP TABLE actions');
        $this->addSql('DROP TABLE zones');
        $this->addSql('DROP TABLE periods');
    }
}
