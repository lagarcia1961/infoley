<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902041447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rol ADD descripcion VARCHAR(100) DEFAULT \'Carga\' NOT NULL');
        $this->addSql("UPDATE rol SET descripcion = 'Administrador' WHERE id = 2");
        $this->addSql('ALTER TABLE user CHANGE rol_id rol_id INT NOT NULL');
        $this->addSql("UPDATE user SET rol_id = 2 WHERE id = 1");//el id 1 es el usuario admin, segun la migracion que creamos al principio

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rol DROP descripcion');
        $this->addSql('ALTER TABLE user CHANGE rol_id rol_id INT DEFAULT 1 NOT NULL');
    }
}
