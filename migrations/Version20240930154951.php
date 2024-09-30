<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930154951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma ADD dependencia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE norma ADD CONSTRAINT FK_3EF6217EDF2432B6 FOREIGN KEY (dependencia_id) REFERENCES dependencia (id)');
        $this->addSql('CREATE INDEX IDX_3EF6217EDF2432B6 ON norma (dependencia_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma DROP FOREIGN KEY FK_3EF6217EDF2432B6');
        $this->addSql('DROP INDEX IDX_3EF6217EDF2432B6 ON norma');
        $this->addSql('ALTER TABLE norma DROP dependencia_id');
    }
}
