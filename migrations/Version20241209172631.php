<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209172631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seccion (id INT AUTO_INCREMENT NOT NULL, tema_id INT NOT NULL, UNIQUE INDEX UNIQ_E0BD15C9A64A8A17 (tema_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE seccion_norma (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, seccion_id INT NOT NULL, norma_id INT NOT NULL, INDEX IDX_6CD3DC8B7A5A413A (seccion_id), INDEX IDX_6CD3DC8BE06FC29E (norma_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE seccion ADD CONSTRAINT FK_E0BD15C9A64A8A17 FOREIGN KEY (tema_id) REFERENCES tema (id)');
        $this->addSql('ALTER TABLE seccion_norma ADD CONSTRAINT FK_6CD3DC8B7A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion (id)');
        $this->addSql('ALTER TABLE seccion_norma ADD CONSTRAINT FK_6CD3DC8BE06FC29E FOREIGN KEY (norma_id) REFERENCES norma (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seccion DROP FOREIGN KEY FK_E0BD15C9A64A8A17');
        $this->addSql('ALTER TABLE seccion_norma DROP FOREIGN KEY FK_6CD3DC8B7A5A413A');
        $this->addSql('ALTER TABLE seccion_norma DROP FOREIGN KEY FK_6CD3DC8BE06FC29E');
        $this->addSql('DROP TABLE seccion');
        $this->addSql('DROP TABLE seccion_norma');
    }
}
