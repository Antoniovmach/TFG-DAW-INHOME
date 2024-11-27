<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530184556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vivienda_foto ADD foto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vivienda_foto ADD CONSTRAINT FK_85E47AC07ABFA656 FOREIGN KEY (foto_id) REFERENCES vivienda (id)');
        $this->addSql('CREATE INDEX IDX_85E47AC07ABFA656 ON vivienda_foto (foto_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vivienda_foto DROP FOREIGN KEY FK_85E47AC07ABFA656');
        $this->addSql('DROP INDEX IDX_85E47AC07ABFA656 ON vivienda_foto');
        $this->addSql('ALTER TABLE vivienda_foto DROP foto_id');
    }
}
