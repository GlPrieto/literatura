<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200425192609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, denominacion VARCHAR(255) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articulo (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, sipnosis LONGTEXT DEFAULT NULL, fecha_publicacion DATETIME NOT NULL, redaccion LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articulo_idioma (articulo_id INT NOT NULL, idioma_id INT NOT NULL, INDEX IDX_FBFCB23C2DBC2FC9 (articulo_id), INDEX IDX_FBFCB23CDEDC0611 (idioma_id), PRIMARY KEY(articulo_id, idioma_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articulo_categoria (articulo_id INT NOT NULL, categoria_id INT NOT NULL, INDEX IDX_B904BF0E2DBC2FC9 (articulo_id), INDEX IDX_B904BF0E3397707A (categoria_id), PRIMARY KEY(articulo_id, categoria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE idioma (id INT AUTO_INCREMENT NOT NULL, denominacion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articulo_idioma ADD CONSTRAINT FK_FBFCB23C2DBC2FC9 FOREIGN KEY (articulo_id) REFERENCES articulo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articulo_idioma ADD CONSTRAINT FK_FBFCB23CDEDC0611 FOREIGN KEY (idioma_id) REFERENCES idioma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articulo_categoria ADD CONSTRAINT FK_B904BF0E2DBC2FC9 FOREIGN KEY (articulo_id) REFERENCES articulo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articulo_categoria ADD CONSTRAINT FK_B904BF0E3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articulo_categoria DROP FOREIGN KEY FK_B904BF0E3397707A');
        $this->addSql('ALTER TABLE articulo_idioma DROP FOREIGN KEY FK_FBFCB23C2DBC2FC9');
        $this->addSql('ALTER TABLE articulo_categoria DROP FOREIGN KEY FK_B904BF0E2DBC2FC9');
        $this->addSql('ALTER TABLE articulo_idioma DROP FOREIGN KEY FK_FBFCB23CDEDC0611');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE articulo');
        $this->addSql('DROP TABLE articulo_idioma');
        $this->addSql('DROP TABLE articulo_categoria');
        $this->addSql('DROP TABLE idioma');
    }
}
