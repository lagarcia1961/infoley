<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808024300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $password = '$2y$13$GZ9jkDqAjxz3FR4xBePPyuj0rHY81EzTXYXreYu7C1YDlVdmgjWyi';
        $this->addSql("INSERT INTO user (id,email,roles,password) VALUES (1,'admin@infoley.com','" . json_encode(['ROLE_ADMIN']) . "','" . $password . "')");
        $this->addSql("INSERT INTO user (id,email,roles,password) VALUES (2,'carga@infoley.com','" . json_encode(['ROLE_CARGA']) . "','" . $password . "')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM user WHERE email IN ('admin@infoley.com', 'carga@infoley.com')");
    }
}
