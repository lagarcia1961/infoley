<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240910161144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8FE1D1C29');
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8629CDFD9');
        $this->addSql('DROP TABLE referencia');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE referencia (id INT AUTO_INCREMENT NOT NULL, tipo_referencia VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, descripcion LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, norma_origen_id INT NOT NULL, norma_destino_id INT NOT NULL, INDEX IDX_C01213D8FE1D1C29 (norma_destino_id), INDEX IDX_C01213D8629CDFD9 (norma_origen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8FE1D1C29 FOREIGN KEY (norma_destino_id) REFERENCES norma (id)');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8629CDFD9 FOREIGN KEY (norma_origen_id) REFERENCES norma (id)');
    }
}
