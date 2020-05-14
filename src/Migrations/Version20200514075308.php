<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514075308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE articulo (id INT AUTO_INCREMENT NOT NULL, idioma_id INT NOT NULL, categoria_id INT NOT NULL, autor_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, sipnosis LONGTEXT DEFAULT NULL, fecha_publicacion DATETIME NOT NULL, redaccion LONGTEXT NOT NULL, imagen VARCHAR(100) DEFAULT NULL, INDEX IDX_69E94E91DEDC0611 (idioma_id), INDEX IDX_69E94E913397707A (categoria_id), INDEX IDX_69E94E9114D45BBE (autor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(25) DEFAULT NULL, apellidos VARCHAR(50) DEFAULT NULL, firma_usuario VARCHAR(25) NOT NULL, imagen_perfil VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articulo ADD CONSTRAINT FK_69E94E91DEDC0611 FOREIGN KEY (idioma_id) REFERENCES idioma (id)');
        $this->addSql('ALTER TABLE articulo ADD CONSTRAINT FK_69E94E913397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE articulo ADD CONSTRAINT FK_69E94E9114D45BBE FOREIGN KEY (autor_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articulo DROP FOREIGN KEY FK_69E94E9114D45BBE');
        $this->addSql('DROP TABLE articulo');
        $this->addSql('DROP TABLE usuario');
    }
}
