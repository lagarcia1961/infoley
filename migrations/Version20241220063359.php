<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220063359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma ADD texto_completo_html LONGTEXT DEFAULT NULL, ADD texto_completo_modificado_html LONGTEXT DEFAULT NULL');
        $this->addSql('UPDATE norma SET texto_completo_html = texto_completo, texto_completo_modificado_html = texto_completo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE norma DROP texto_completo_html, DROP texto_completo_modificado_html');
    }
}
