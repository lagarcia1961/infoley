<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808150601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario_tipo_norma (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tipo_norma_id INT NOT NULL, INDEX IDX_CB7AF114A76ED395 (user_id), INDEX IDX_CB7AF11436AA9857 (tipo_norma_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE usuario_tipo_norma ADD CONSTRAINT FK_CB7AF114A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usuario_tipo_norma ADD CONSTRAINT FK_CB7AF11436AA9857 FOREIGN KEY (tipo_norma_id) REFERENCES tipo_norma (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_tipo_norma DROP FOREIGN KEY FK_CB7AF114A76ED395');
        $this->addSql('ALTER TABLE usuario_tipo_norma DROP FOREIGN KEY FK_CB7AF11436AA9857');
        $this->addSql('DROP TABLE usuario_tipo_norma');
    }
}
