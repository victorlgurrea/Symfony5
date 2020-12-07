<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207132339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, color VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etiqueta (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marcador (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, creado DATETIME NOT NULL, favorito TINYINT(1) DEFAULT NULL, INDEX IDX_B5F18E73397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marcador_etiqueta (id INT AUTO_INCREMENT NOT NULL, marcador_id INT DEFAULT NULL, etiqueta_id INT DEFAULT NULL, creado DATETIME NOT NULL, INDEX IDX_DCF4C7BB323D722 (marcador_id), INDEX IDX_DCF4C7BD53DA3AB (etiqueta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marcador ADD CONSTRAINT FK_B5F18E73397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marcador DROP FOREIGN KEY FK_B5F18E73397707A');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BD53DA3AB');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BB323D722');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE etiqueta');
        $this->addSql('DROP TABLE marcador');
        $this->addSql('DROP TABLE marcador_etiqueta');
    }
}
