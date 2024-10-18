<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018053944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma ADD slug VARCHAR(300) DEFAULT NULL');
        $this->addSql("UPDATE norma SET slug = CONCAT(REPLACE(titulo, ' ', '-'), '-', id)");
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EF6217E989D9B62 ON norma (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3EF6217E989D9B62 ON norma');
        $this->addSql('ALTER TABLE norma DROP slug');
    }
}
