<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809122649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD nombre VARCHAR(100) DEFAULT NULL, ADD usuario VARCHAR(100) DEFAULT NULL');
        $this->addSql('UPDATE user set nombre = "admin", usuario="admin" where email = "admin@infoley.com"');
        $this->addSql('UPDATE user set nombre = "carga", usuario="carga" where email = "carga@infoley.com"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP nombre, DROP usuario');
    }
}
