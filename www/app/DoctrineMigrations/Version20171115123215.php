<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171115123215 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pin (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B5852DF38D9F6D38 (order_id), INDEX IDX_B5852DF3D0C07AFF (promo_id), UNIQUE INDEX unique_pins_inside_one_promo (promo_id, value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_promo (product_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_114FE1A74584665A (product_id), INDEX IDX_114FE1A7D0C07AFF (promo_id), PRIMARY KEY(product_id, promo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, timeunit_id INT DEFAULT NULL, area_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start DATE DEFAULT NULL, end DATE DEFAULT NULL, is_active TINYINT(1) NOT NULL, code VARCHAR(255) NOT NULL, discount DOUBLE PRECISION NOT NULL, amount INT DEFAULT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B0139AFB5E237E06 (name), UNIQUE INDEX UNIQ_B0139AFB77153098 (code), INDEX IDX_B0139AFB5878D878 (timeunit_id), INDEX IDX_B0139AFBBD0F409C (area_id), INDEX IDX_B0139AFB9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pin ADD CONSTRAINT FK_B5852DF38D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE pin ADD CONSTRAINT FK_B5852DF3D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE product_promo ADD CONSTRAINT FK_114FE1A74584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_promo ADD CONSTRAINT FK_114FE1A7D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB5878D878 FOREIGN KEY (timeunit_id) REFERENCES timeunit (id)');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFBBD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE item ADD promo_id INT DEFAULT NULL, ADD discount_coef DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251ED0C07AFF ON item (promo_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_tariff ON tariff (product_id, timeunit_id, delivery_id, zone_id, medium_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pin DROP FOREIGN KEY FK_B5852DF3D0C07AFF');
        $this->addSql('ALTER TABLE product_promo DROP FOREIGN KEY FK_114FE1A7D0C07AFF');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED0C07AFF');
        $this->addSql('DROP TABLE pin');
        $this->addSql('DROP TABLE product_promo');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP INDEX IDX_1F1B251ED0C07AFF ON item');
        $this->addSql('ALTER TABLE item DROP promo_id, DROP discount_coef');
        $this->addSql('DROP INDEX unique_tariff ON tariff');
    }
}
