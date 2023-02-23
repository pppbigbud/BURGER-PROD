<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018054410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tacos ADD size_id INT DEFAULT NULL, ADD meat_id INT DEFAULT NULL, ADD sauce_id INT DEFAULT NULL, ADD cheese_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tacos ADD CONSTRAINT FK_281203E3498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('ALTER TABLE tacos ADD CONSTRAINT FK_281203E3F63B19A6 FOREIGN KEY (meat_id) REFERENCES meat (id)');
        $this->addSql('ALTER TABLE tacos ADD CONSTRAINT FK_281203E37AB984B7 FOREIGN KEY (sauce_id) REFERENCES sauce (id)');
        $this->addSql('ALTER TABLE tacos ADD CONSTRAINT FK_281203E32AD46E66 FOREIGN KEY (cheese_id) REFERENCES cheese (id)');
        $this->addSql('CREATE INDEX IDX_281203E3498DA827 ON tacos (size_id)');
        $this->addSql('CREATE INDEX IDX_281203E3F63B19A6 ON tacos (meat_id)');
        $this->addSql('CREATE INDEX IDX_281203E37AB984B7 ON tacos (sauce_id)');
        $this->addSql('CREATE INDEX IDX_281203E32AD46E66 ON tacos (cheese_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tacos DROP FOREIGN KEY FK_281203E3498DA827');
        $this->addSql('ALTER TABLE tacos DROP FOREIGN KEY FK_281203E3F63B19A6');
        $this->addSql('ALTER TABLE tacos DROP FOREIGN KEY FK_281203E37AB984B7');
        $this->addSql('ALTER TABLE tacos DROP FOREIGN KEY FK_281203E32AD46E66');
        $this->addSql('DROP INDEX IDX_281203E3498DA827 ON tacos');
        $this->addSql('DROP INDEX IDX_281203E3F63B19A6 ON tacos');
        $this->addSql('DROP INDEX IDX_281203E37AB984B7 ON tacos');
        $this->addSql('DROP INDEX IDX_281203E32AD46E66 ON tacos');
        $this->addSql('ALTER TABLE tacos DROP size_id, DROP meat_id, DROP sauce_id, DROP cheese_id');
    }
}
