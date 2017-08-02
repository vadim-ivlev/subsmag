<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170802132909 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE issue (id INT AUTO_INCREMENT NOT NULL, `month` SMALLINT NOT NULL, `year` SMALLINT NOT NULL, description LONGTEXT NOT NULL, `text` LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patriff (id INT AUTO_INCREMENT NOT NULL, issue_id INT DEFAULT NULL, medium_id INT DEFAULT NULL, delivery_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_AFB805515E7AA58C (issue_id), INDEX IDX_AFB80551E252B6A5 (medium_id), INDEX IDX_AFB8055112136921 (delivery_id), INDEX IDX_AFB805519F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE summary (id INT AUTO_INCREMENT NOT NULL, issue_id INT DEFAULT NULL, title LONGTEXT NOT NULL, `text` LONGTEXT NOT NULL, page SMALLINT NOT NULL, INDEX IDX_CE2866635E7AA58C (issue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patritem (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, patriff_id INT DEFAULT NULL, quantity INT NOT NULL, cost DOUBLE PRECISION NOT NULL, INDEX IDX_C94D0FD28D9F6D38 (order_id), INDEX IDX_C94D0FD2B3F24EE3 (patriff_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patriff ADD CONSTRAINT FK_AFB805515E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id)');
        $this->addSql('ALTER TABLE patriff ADD CONSTRAINT FK_AFB80551E252B6A5 FOREIGN KEY (medium_id) REFERENCES medium (id)');
        $this->addSql('ALTER TABLE patriff ADD CONSTRAINT FK_AFB8055112136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE patriff ADD CONSTRAINT FK_AFB805519F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE summary ADD CONSTRAINT FK_CE2866635E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id)');
        $this->addSql('ALTER TABLE patritem ADD CONSTRAINT FK_C94D0FD28D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE patritem ADD CONSTRAINT FK_C94D0FD2B3F24EE3 FOREIGN KEY (patriff_id) REFERENCES patriff (id)');
        $this->addSql('ALTER TABLE `order` CHANGE sum total DOUBLE PRECISION NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE patriff DROP FOREIGN KEY FK_AFB805515E7AA58C');
        $this->addSql('ALTER TABLE summary DROP FOREIGN KEY FK_CE2866635E7AA58C');
        $this->addSql('ALTER TABLE patritem DROP FOREIGN KEY FK_C94D0FD2B3F24EE3');
        $this->addSql('DROP TABLE issue');
        $this->addSql('DROP TABLE patriff');
        $this->addSql('DROP TABLE summary');
        $this->addSql('DROP TABLE patritem');
        $this->addSql('ALTER TABLE `order` CHANGE total sum DOUBLE PRECISION NOT NULL');
    }
}
