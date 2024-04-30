<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403140223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY FK_17EB513AC11E4628');
        $this->addSql('DROP INDEX IDX_17EB513AC11E4628 ON facturation');
        $this->addSql('ALTER TABLE facturation ADD date_reglement DATETIME DEFAULT NULL, CHANGE mode_payment_id mode_paie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT FK_17EB513A62E04A07 FOREIGN KEY (mode_paie_id) REFERENCES mode_paiement (id)');
        $this->addSql('CREATE INDEX IDX_17EB513A62E04A07 ON facturation (mode_paie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY FK_17EB513A62E04A07');
        $this->addSql('DROP INDEX IDX_17EB513A62E04A07 ON facturation');
        $this->addSql('ALTER TABLE facturation DROP date_reglement, CHANGE mode_paie_id mode_payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT FK_17EB513AC11E4628 FOREIGN KEY (mode_payment_id) REFERENCES mode_paiement (id)');
        $this->addSql('CREATE INDEX IDX_17EB513AC11E4628 ON facturation (mode_payment_id)');
    }
}
