<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531193348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vivienda_foto DROP FOREIGN KEY FK_85E47AC081A75788');
        $this->addSql('DROP INDEX IDX_85E47AC081A75788 ON vivienda_foto');
        $this->addSql('ALTER TABLE vivienda_foto DROP vivienda_id, DROP foto_url');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vivienda_foto ADD vivienda_id INT DEFAULT NULL, ADD foto_url VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE vivienda_foto ADD CONSTRAINT FK_85E47AC081A75788 FOREIGN KEY (vivienda_id) REFERENCES vivienda (id)');
        $this->addSql('CREATE INDEX IDX_85E47AC081A75788 ON vivienda_foto (vivienda_id)');
    }
}
