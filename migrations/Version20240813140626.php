<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240813140626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE tipo_auditoria set descripcion = 'INSERTÓ' where id = 1");
        $this->addSql("INSERT INTO tipo_auditoria (id, descripcion) VALUES (2,'MODIFICÓ')");
        $this->addSql("INSERT INTO tipo_auditoria (id, descripcion) VALUES (3,'ELIMINÓ')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
