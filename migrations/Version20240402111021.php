<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402111021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense ADD mode_paiement_id INT NOT NULL, ADD traite_par_id INT NOT NULL, ADD caisse_retrait_id INT NOT NULL');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757438F5B63 FOREIGN KEY (mode_paiement_id) REFERENCES mode_paiement (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757167FABE8 FOREIGN KEY (traite_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_340597571D002A8F FOREIGN KEY (caisse_retrait_id) REFERENCES caisse (id)');
        $this->addSql('CREATE INDEX IDX_34059757438F5B63 ON depense (mode_paiement_id)');
        $this->addSql('CREATE INDEX IDX_34059757167FABE8 ON depense (traite_par_id)');
        $this->addSql('CREATE INDEX IDX_340597571D002A8F ON depense (caisse_retrait_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757438F5B63');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757167FABE8');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_340597571D002A8F');
        $this->addSql('DROP INDEX IDX_34059757438F5B63 ON depense');
        $this->addSql('DROP INDEX IDX_34059757167FABE8 ON depense');
        $this->addSql('DROP INDEX IDX_340597571D002A8F ON depense');
        $this->addSql('ALTER TABLE depense DROP mode_paiement_id, DROP traite_par_id, DROP caisse_retrait_id');
    }
}
