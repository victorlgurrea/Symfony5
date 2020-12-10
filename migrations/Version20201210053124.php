<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210053124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marcador_etiqueta ADD etiqueta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id)');
        $this->addSql('CREATE INDEX IDX_DCF4C7BD53DA3AB ON marcador_etiqueta (etiqueta_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BB323D722');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BD53DA3AB');
        $this->addSql('DROP INDEX IDX_DCF4C7BD53DA3AB ON marcador_etiqueta');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP etiqueta_id');
    }
}
