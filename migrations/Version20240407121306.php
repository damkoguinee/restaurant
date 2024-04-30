<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407121306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse CHANGE saisie_par_id saisie_par_id INT NOT NULL');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFEC74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFEC74AC7FE ON mouvement_caisse (saisie_par_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFEC74AC7FE');
        $this->addSql('DROP INDEX IDX_C8E3DDFEC74AC7FE ON mouvement_caisse');
        $this->addSql('ALTER TABLE mouvement_caisse CHANGE saisie_par_id saisie_par_id INT DEFAULT 1 NOT NULL');
    }
}
