<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407080752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse ADD versement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFEDBBF8D62 FOREIGN KEY (versement_id) REFERENCES versement (id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFEDBBF8D62 ON mouvement_caisse (versement_id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD versement_id INT DEFAULT NULL, ADD ajustement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A2DBBF8D62 FOREIGN KEY (versement_id) REFERENCES versement (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A2503C6C15 FOREIGN KEY (ajustement_id) REFERENCES ajustement_solde (id)');
        $this->addSql('CREATE INDEX IDX_C47441A2DBBF8D62 ON mouvement_collaborateur (versement_id)');
        $this->addSql('CREATE INDEX IDX_C47441A2503C6C15 ON mouvement_collaborateur (ajustement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFEDBBF8D62');
        $this->addSql('DROP INDEX IDX_C8E3DDFEDBBF8D62 ON mouvement_caisse');
        $this->addSql('ALTER TABLE mouvement_caisse DROP versement_id');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A2DBBF8D62');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A2503C6C15');
        $this->addSql('DROP INDEX IDX_C47441A2DBBF8D62 ON mouvement_collaborateur');
        $this->addSql('DROP INDEX IDX_C47441A2503C6C15 ON mouvement_collaborateur');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP versement_id, DROP ajustement_id');
    }
}
