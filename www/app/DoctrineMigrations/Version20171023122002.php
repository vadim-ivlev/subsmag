<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171023122002 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE postal_index (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, timeblock_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C75C25AD4584665A (product_id), INDEX IDX_C75C25ADC922C217 (timeblock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeblock (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE postal_index ADD CONSTRAINT FK_C75C25AD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE postal_index ADD CONSTRAINT FK_C75C25ADC922C217 FOREIGN KEY (timeblock_id) REFERENCES timeblock (id)');
        $this->addSql('ALTER TABLE product DROP postal_index');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE postal_index DROP FOREIGN KEY FK_C75C25ADC922C217');
        $this->addSql('DROP TABLE postal_index');
        $this->addSql('DROP TABLE timeblock');
        $this->addSql('ALTER TABLE product ADD postal_index VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci');
    }
}
