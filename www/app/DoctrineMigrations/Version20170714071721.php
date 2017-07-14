<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170714071721 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_tariff (order_id INT NOT NULL, tariff_id INT NOT NULL, INDEX IDX_57EA30A88D9F6D38 (order_id), INDEX IDX_57EA30A892348FD2 (tariff_id), PRIMARY KEY(order_id, tariff_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_edition (product_id INT NOT NULL, edition_id INT NOT NULL, INDEX IDX_794803AF4584665A (product_id), INDEX IDX_794803AF74281A5E (edition_id), PRIMARY KEY(product_id, edition_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_tariff ADD CONSTRAINT FK_57EA30A88D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_tariff ADD CONSTRAINT FK_57EA30A892348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_edition ADD CONSTRAINT FK_794803AF4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_edition ADD CONSTRAINT FK_794803AF74281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE INDEX IDX_F52993984C3A3BB ON `order` (payment_id)');
        $this->addSql('ALTER TABLE tariff ADD product_id INT DEFAULT NULL, ADD period_id INT DEFAULT NULL, ADD delivery_id INT DEFAULT NULL, ADD zone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207DEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('CREATE INDEX IDX_9465207D4584665A ON tariff (product_id)');
        $this->addSql('CREATE INDEX IDX_9465207DEC8B7ADE ON tariff (period_id)');
        $this->addSql('CREATE INDEX IDX_9465207D12136921 ON tariff (delivery_id)');
        $this->addSql('CREATE INDEX IDX_9465207D9F2C3FAB ON tariff (zone_id)');
        $this->addSql('ALTER TABLE `interval` ADD period_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `interval` ADD CONSTRAINT FK_19C6EFCBEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('CREATE INDEX IDX_19C6EFCBEC8B7ADE ON `interval` (period_id)');
        $this->addSql('ALTER TABLE area ADD zone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D689F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('CREATE INDEX IDX_D7943D689F2C3FAB ON area (zone_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE order_tariff');
        $this->addSql('DROP TABLE product_edition');
        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D689F2C3FAB');
        $this->addSql('DROP INDEX IDX_D7943D689F2C3FAB ON area');
        $this->addSql('ALTER TABLE area DROP zone_id');
        $this->addSql('ALTER TABLE `interval` DROP FOREIGN KEY FK_19C6EFCBEC8B7ADE');
        $this->addSql('DROP INDEX IDX_19C6EFCBEC8B7ADE ON `interval`');
        $this->addSql('ALTER TABLE `interval` DROP period_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984C3A3BB');
        $this->addSql('DROP INDEX IDX_F52993984C3A3BB ON `order`');
        $this->addSql('ALTER TABLE `order` DROP payment_id');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D4584665A');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207DEC8B7ADE');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D12136921');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D9F2C3FAB');
        $this->addSql('DROP INDEX IDX_9465207D4584665A ON tariff');
        $this->addSql('DROP INDEX IDX_9465207DEC8B7ADE ON tariff');
        $this->addSql('DROP INDEX IDX_9465207D12136921 ON tariff');
        $this->addSql('DROP INDEX IDX_9465207D9F2C3FAB ON tariff');
        $this->addSql('ALTER TABLE tariff DROP product_id, DROP period_id, DROP delivery_id, DROP zone_id');
    }
}
