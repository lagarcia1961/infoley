<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930145222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dependencia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE referencia (id INT AUTO_INCREMENT NOT NULL, tipo_referencia_id INT NOT NULL, norma_origen_id INT DEFAULT NULL, norma_destino_id INT DEFAULT NULL, INDEX IDX_C01213D8773EC2FC (tipo_referencia_id), INDEX IDX_C01213D8629CDFD9 (norma_origen_id), INDEX IDX_C01213D8FE1D1C29 (norma_destino_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8773EC2FC FOREIGN KEY (tipo_referencia_id) REFERENCES tipo_referencia (id)');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8629CDFD9 FOREIGN KEY (norma_origen_id) REFERENCES norma (id)');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8FE1D1C29 FOREIGN KEY (norma_destino_id) REFERENCES norma (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8773EC2FC');
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8629CDFD9');
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8FE1D1C29');
        $this->addSql('DROP TABLE dependencia');
        $this->addSql('DROP TABLE referencia');
    }
}
