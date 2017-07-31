<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170731140115 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1CF98C70');
        $this->addSql('ALTER TABLE good DROP FOREIGN KEY FK_6C844E92EC8B7ADE');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, month_id INT DEFAULT NULL, area_id INT DEFAULT NULL, start DATE NOT NULL, end DATE NOT NULL, is_regional TINYINT(1) NOT NULL, INDEX IDX_E54BC0054584665A (product_id), INDEX IDX_E54BC005A0CBDE4 (month_id), INDEX IDX_E54BC005BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `month` (id INT AUTO_INCREMENT NOT NULL, `number` SMALLINT NOT NULL, year SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0054584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005A0CBDE4 FOREIGN KEY (month_id) REFERENCES `month` (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('DROP TABLE good');
        $this->addSql('DROP TABLE period');
        $this->addSql('DROP INDEX IDX_1F1B251E1CF98C70 ON item');
        $this->addSql('ALTER TABLE item ADD timeunit_amount INT NOT NULL, CHANGE good_id sale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E4A7E4868 ON item (sale_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4A7E4868');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005A0CBDE4');
        $this->addSql('CREATE TABLE good (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, area_id INT DEFAULT NULL, period_id INT DEFAULT NULL, start DATE NOT NULL, end DATE NOT NULL, is_regional TINYINT(1) NOT NULL, INDEX IDX_6C844E924584665A (product_id), INDEX IDX_6C844E92EC8B7ADE (period_id), INDEX IDX_6C844E92BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, first_month SMALLINT NOT NULL, duration SMALLINT NOT NULL, year SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E924584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E92BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E92EC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE `month`');
        $this->addSql('DROP INDEX IDX_1F1B251E4A7E4868 ON item');
        $this->addSql('ALTER TABLE item DROP timeunit_amount, CHANGE sale_id good_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1CF98C70 FOREIGN KEY (good_id) REFERENCES good (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E1CF98C70 ON item (good_id)');
    }
}
