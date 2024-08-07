<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806153300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE norma_tema (id INT AUTO_INCREMENT NOT NULL, tema_id INT NOT NULL, norma_id INT NOT NULL, INDEX IDX_922657C9A64A8A17 (tema_id), INDEX IDX_922657C9E06FC29E (norma_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE norma_tema ADD CONSTRAINT FK_922657C9A64A8A17 FOREIGN KEY (tema_id) REFERENCES tema (id)');
        $this->addSql('ALTER TABLE norma_tema ADD CONSTRAINT FK_922657C9E06FC29E FOREIGN KEY (norma_id) REFERENCES norma (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma_tema DROP FOREIGN KEY FK_922657C9A64A8A17');
        $this->addSql('ALTER TABLE norma_tema DROP FOREIGN KEY FK_922657C9E06FC29E');
        $this->addSql('DROP TABLE norma_tema');
    }
}
