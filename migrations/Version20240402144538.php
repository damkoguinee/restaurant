<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402144538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFEB0B7A971 FOREIGN KEY (paiement_salaire_id) REFERENCES paiement_salaire_personnel (id)');
        $this->addSql('ALTER TABLE prime_personnel CHANGE commentaire commentaires VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFEB0B7A971');
        $this->addSql('ALTER TABLE prime_personnel CHANGE commentaires commentaire VARCHAR(255) DEFAULT NULL');
    }
}
