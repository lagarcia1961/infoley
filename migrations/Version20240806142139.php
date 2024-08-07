<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806142139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documento_adicional (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, url_documento VARCHAR(255) DEFAULT NULL, norma_id INT NOT NULL, INDEX IDX_2007794BE06FC29E (norma_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE norma (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, anio INT NOT NULL, titulo VARCHAR(255) NOT NULL, fecha_publicacion DATE DEFAULT NULL, texto_completo LONGTEXT DEFAULT NULL, url_pdf VARCHAR(255) DEFAULT NULL, tipo_id INT NOT NULL, INDEX IDX_3EF6217EA9276E6C (tipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE referencia (id INT AUTO_INCREMENT NOT NULL, tipo_referencia VARCHAR(100) NOT NULL, descripcion LONGTEXT DEFAULT NULL, norma_origen_id INT NOT NULL, norma_destino_id INT NOT NULL, INDEX IDX_C01213D8629CDFD9 (norma_origen_id), INDEX IDX_C01213D8FE1D1C29 (norma_destino_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tema (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE documento_adicional ADD CONSTRAINT FK_2007794BE06FC29E FOREIGN KEY (norma_id) REFERENCES norma (id)');
        $this->addSql('ALTER TABLE norma ADD CONSTRAINT FK_3EF6217EA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_norma (id)');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8629CDFD9 FOREIGN KEY (norma_origen_id) REFERENCES norma (id)');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8FE1D1C29 FOREIGN KEY (norma_destino_id) REFERENCES norma (id)');
        $this->addSql('ALTER TABLE tipo_norma ADD nombre VARCHAR(100) NOT NULL, CHANGE descripcion descripcion LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documento_adicional DROP FOREIGN KEY FK_2007794BE06FC29E');
        $this->addSql('ALTER TABLE norma DROP FOREIGN KEY FK_3EF6217EA9276E6C');
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8629CDFD9');
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8FE1D1C29');
        $this->addSql('DROP TABLE documento_adicional');
        $this->addSql('DROP TABLE norma');
        $this->addSql('DROP TABLE referencia');
        $this->addSql('DROP TABLE tema');
        $this->addSql('ALTER TABLE tipo_norma DROP nombre, CHANGE descripcion descripcion VARCHAR(50) NOT NULL');
    }
}
