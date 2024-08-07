<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806144528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma DROP FOREIGN KEY FK_3EF6217EA9276E6C');
        $this->addSql('DROP INDEX IDX_3EF6217EA9276E6C ON norma');
        $this->addSql('ALTER TABLE norma CHANGE tipo_id tipo_norma_id INT NOT NULL');
        $this->addSql('ALTER TABLE norma ADD CONSTRAINT FK_3EF6217E36AA9857 FOREIGN KEY (tipo_norma_id) REFERENCES tipo_norma (id)');
        $this->addSql('CREATE INDEX IDX_3EF6217E36AA9857 ON norma (tipo_norma_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma DROP FOREIGN KEY FK_3EF6217E36AA9857');
        $this->addSql('DROP INDEX IDX_3EF6217E36AA9857 ON norma');
        $this->addSql('ALTER TABLE norma CHANGE tipo_norma_id tipo_id INT NOT NULL');
        $this->addSql('ALTER TABLE norma ADD CONSTRAINT FK_3EF6217EA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_norma (id)');
        $this->addSql('CREATE INDEX IDX_3EF6217EA9276E6C ON norma (tipo_id)');
    }
}
