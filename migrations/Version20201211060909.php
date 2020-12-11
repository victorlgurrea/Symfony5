<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201211060909 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marcador DROP FOREIGN KEY FK_B5F18E73397707A');
        $this->addSql('ALTER TABLE marcador ADD CONSTRAINT FK_B5F18E73397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marcador DROP FOREIGN KEY FK_B5F18E73397707A');
        $this->addSql('ALTER TABLE marcador ADD CONSTRAINT FK_B5F18E73397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
    }
}
