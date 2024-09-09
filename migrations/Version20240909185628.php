<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909185628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma ADD norma_origen_id INT DEFAULT NULL, ADD norma_destino_id INT DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE norma ADD CONSTRAINT FK_3EF6217E629CDFD9 FOREIGN KEY (norma_origen_id) REFERENCES norma (id)');
        $this->addSql('ALTER TABLE norma ADD CONSTRAINT FK_3EF6217EFE1D1C29 FOREIGN KEY (norma_destino_id) REFERENCES norma (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EF6217E629CDFD9 ON norma (norma_origen_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EF6217EFE1D1C29 ON norma (norma_destino_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma DROP FOREIGN KEY FK_3EF6217E629CDFD9');
        $this->addSql('ALTER TABLE norma DROP FOREIGN KEY FK_3EF6217EFE1D1C29');
        $this->addSql('DROP INDEX UNIQ_3EF6217E629CDFD9 ON norma');
        $this->addSql('DROP INDEX UNIQ_3EF6217EFE1D1C29 ON norma');
        $this->addSql('ALTER TABLE norma DROP norma_origen_id, DROP norma_destino_id, CHANGE is_active is_active TINYINT(1) NOT NULL');
    }
}
