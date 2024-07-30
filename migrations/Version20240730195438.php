<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240730195438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auditoria (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, tipo_auditoria_id INT NOT NULL, INDEX IDX_AF4BB49D417E4398 (tipo_auditoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE auditoria ADD CONSTRAINT FK_AF4BB49D417E4398 FOREIGN KEY (tipo_auditoria_id) REFERENCES tipo_auditoria (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auditoria DROP FOREIGN KEY FK_AF4BB49D417E4398');
        $this->addSql('DROP TABLE auditoria');
    }
}
