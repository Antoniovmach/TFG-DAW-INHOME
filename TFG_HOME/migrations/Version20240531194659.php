<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531194659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE viviendafot DROP FOREIGN KEY FK_E1F03E75342F7251');
        $this->addSql('DROP TABLE viviendafot');
        $this->addSql('ALTER TABLE vivienda_foto ADD vivienda_id INT DEFAULT NULL, ADD foto_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vivienda_foto ADD CONSTRAINT FK_85E47AC081A75788 FOREIGN KEY (vivienda_id) REFERENCES vivienda (id)');
        $this->addSql('CREATE INDEX IDX_85E47AC081A75788 ON vivienda_foto (vivienda_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE viviendafot (id INT AUTO_INCREMENT NOT NULL, vivienda_id_id INT DEFAULT NULL, foto_url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E1F03E75342F7251 (vivienda_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE viviendafot ADD CONSTRAINT FK_E1F03E75342F7251 FOREIGN KEY (vivienda_id_id) REFERENCES vivienda (id)');
        $this->addSql('ALTER TABLE vivienda_foto DROP FOREIGN KEY FK_85E47AC081A75788');
        $this->addSql('DROP INDEX IDX_85E47AC081A75788 ON vivienda_foto');
        $this->addSql('ALTER TABLE vivienda_foto DROP vivienda_id, DROP foto_url');
    }
}
