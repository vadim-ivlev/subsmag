<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170726131911 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_timeblock (order_id INT NOT NULL, timeblock_id INT NOT NULL, INDEX IDX_CF0495EC8D9F6D38 (order_id), INDEX IDX_CF0495ECC922C217 (timeblock_id), PRIMARY KEY(order_id, timeblock_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeblock (id INT AUTO_INCREMENT NOT NULL, decimal_view SMALLINT NOT NULL, year SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeblock_tariff (timeblock_id INT NOT NULL, tariff_id INT NOT NULL, INDEX IDX_5D45C0E1C922C217 (timeblock_id), INDEX IDX_5D45C0E192348FD2 (tariff_id), PRIMARY KEY(timeblock_id, tariff_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_timeblock ADD CONSTRAINT FK_CF0495EC8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_timeblock ADD CONSTRAINT FK_CF0495ECC922C217 FOREIGN KEY (timeblock_id) REFERENCES timeblock (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE timeblock_tariff ADD CONSTRAINT FK_5D45C0E1C922C217 FOREIGN KEY (timeblock_id) REFERENCES timeblock (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE timeblock_tariff ADD CONSTRAINT FK_5D45C0E192348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE order_tariff');
        $this->addSql('ALTER TABLE `interval` DROP FOREIGN KEY FK_19C6EFCBEC8B7ADE');
        $this->addSql('DROP INDEX IDX_19C6EFCBEC8B7ADE ON `interval`');
        $this->addSql('ALTER TABLE `interval` ADD area_id INT DEFAULT NULL, CHANGE period_id timeblock_id INT DEFAULT NULL, CHANGE is_moscow is_regional TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE `interval` ADD CONSTRAINT FK_19C6EFCBC922C217 FOREIGN KEY (timeblock_id) REFERENCES timeblock (id)');
        $this->addSql('ALTER TABLE `interval` ADD CONSTRAINT FK_19C6EFCBBD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('CREATE INDEX IDX_19C6EFCBC922C217 ON `interval` (timeblock_id)');
        $this->addSql('CREATE INDEX IDX_19C6EFCBBD0F409C ON `interval` (area_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_timeblock DROP FOREIGN KEY FK_CF0495ECC922C217');
        $this->addSql('ALTER TABLE timeblock_tariff DROP FOREIGN KEY FK_5D45C0E1C922C217');
        $this->addSql('ALTER TABLE `interval` DROP FOREIGN KEY FK_19C6EFCBC922C217');
        $this->addSql('CREATE TABLE order_tariff (order_id INT NOT NULL, tariff_id INT NOT NULL, INDEX IDX_57EA30A88D9F6D38 (order_id), INDEX IDX_57EA30A892348FD2 (tariff_id), PRIMARY KEY(order_id, tariff_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_tariff ADD CONSTRAINT FK_57EA30A88D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_tariff ADD CONSTRAINT FK_57EA30A892348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE order_timeblock');
        $this->addSql('DROP TABLE timeblock');
        $this->addSql('DROP TABLE timeblock_tariff');
        $this->addSql('ALTER TABLE `interval` DROP FOREIGN KEY FK_19C6EFCBBD0F409C');
        $this->addSql('DROP INDEX IDX_19C6EFCBC922C217 ON `interval`');
        $this->addSql('DROP INDEX IDX_19C6EFCBBD0F409C ON `interval`');
        $this->addSql('ALTER TABLE `interval` ADD period_id INT DEFAULT NULL, DROP timeblock_id, DROP area_id, CHANGE is_regional is_moscow TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE `interval` ADD CONSTRAINT FK_19C6EFCBEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('CREATE INDEX IDX_19C6EFCBEC8B7ADE ON `interval` (period_id)');
    }
}
