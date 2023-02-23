<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021071821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE frie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE burger ADD frie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE burger ADD CONSTRAINT FK_EFE35A0D323A0444 FOREIGN KEY (frie_id) REFERENCES frie (id)');
        $this->addSql('CREATE INDEX IDX_EFE35A0D323A0444 ON burger (frie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE burger DROP FOREIGN KEY FK_EFE35A0D323A0444');
        $this->addSql('DROP TABLE frie');
        $this->addSql('DROP INDEX IDX_EFE35A0D323A0444 ON burger');
        $this->addSql('ALTER TABLE burger DROP frie_id');
    }
}
