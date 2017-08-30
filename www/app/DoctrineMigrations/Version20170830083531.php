<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170830083531 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE item ');

        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, month_id INT DEFAULT NULL, tariff_id INT DEFAULT NULL, duration SMALLINT NOT NULL, quantity INT NOT NULL, timeunit_amount INT NOT NULL, cost DOUBLE PRECISION NOT NULL, INDEX IDX_1F1B251E8D9F6D38 (order_id), INDEX IDX_1F1B251EA0CBDE4 (month_id), INDEX IDX_1F1B251E92348FD2 (tariff_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA0CBDE4 FOREIGN KEY (month_id) REFERENCES `month` (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E92348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE item');
    }
}
