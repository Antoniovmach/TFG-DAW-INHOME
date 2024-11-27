<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605190424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilidad_vivienda ADD vivienda_id INT DEFAULT NULL, CHANGE fecha fecha DATETIME NOT NULL');
        $this->addSql('ALTER TABLE disponibilidad_vivienda ADD CONSTRAINT FK_E309B11281A75788 FOREIGN KEY (vivienda_id) REFERENCES vivienda (id)');
        $this->addSql('CREATE INDEX IDX_E309B11281A75788 ON disponibilidad_vivienda (vivienda_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilidad_vivienda DROP FOREIGN KEY FK_E309B11281A75788');
        $this->addSql('DROP INDEX IDX_E309B11281A75788 ON disponibilidad_vivienda');
        $this->addSql('ALTER TABLE disponibilidad_vivienda DROP vivienda_id, CHANGE fecha fecha DATE NOT NULL');
    }
}
