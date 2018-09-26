<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180921130310 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, discount DOUBLE PRECISION NOT NULL, INDEX IDX_E1E0B40E4584665A (product_id), INDEX IDX_E1E0B40ED0C07AFF (promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40ED0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('DROP TABLE product_promo');
        $this->addSql('ALTER TABLE promo DROP discount');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_promo (product_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_114FE1A74584665A (product_id), INDEX IDX_114FE1A7D0C07AFF (promo_id), PRIMARY KEY(product_id, promo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_promo ADD CONSTRAINT FK_114FE1A74584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_promo ADD CONSTRAINT FK_114FE1A7D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE discount');
        $this->addSql('ALTER TABLE promo ADD discount DOUBLE PRECISION NOT NULL');
    }
}
