<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209115751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gutschein (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, gs_name VARCHAR(255) NOT NULL, gs_betrag INT NOT NULL, gs_bemerkung LONGTEXT DEFAULT NULL, gs_date DATE NOT NULL, is_payed TINYINT(1) NOT NULL, hash VARCHAR(255) NOT NULL, INDEX IDX_DF03AB6BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gutschein ADD CONSTRAINT FK_DF03AB6BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gutschein');
    }
}
