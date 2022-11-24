<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124073917 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gift_name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, christmas_day TINYINT(1) DEFAULT \'0\' NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_gift (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, gift_id INT NOT NULL, day INT NOT NULL, INDEX IDX_2E9A1D26217BBB47 (person_id), INDEX IDX_2E9A1D2697A95A83 (gift_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_gift ADD CONSTRAINT FK_2E9A1D26217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_gift ADD CONSTRAINT FK_2E9A1D2697A95A83 FOREIGN KEY (gift_id) REFERENCES person (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person_gift DROP FOREIGN KEY FK_2E9A1D26217BBB47');
        $this->addSql('ALTER TABLE person_gift DROP FOREIGN KEY FK_2E9A1D2697A95A83');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_gift');
    }
}
