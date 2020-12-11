<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201211060700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122DDB38439E');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122DDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etiqueta DROP FOREIGN KEY FK_6D5CA63ADB38439E');
        $this->addSql('ALTER TABLE etiqueta ADD CONSTRAINT FK_6D5CA63ADB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BB323D722');
        $this->addSql('ALTER TABLE marcador_etiqueta CHANGE etiqueta_id etiqueta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122DDB38439E');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122DDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE etiqueta DROP FOREIGN KEY FK_6D5CA63ADB38439E');
        $this->addSql('ALTER TABLE etiqueta ADD CONSTRAINT FK_6D5CA63ADB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BB323D722');
        $this->addSql('ALTER TABLE marcador_etiqueta CHANGE etiqueta_id etiqueta_id INT NOT NULL');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id)');
    }
}
