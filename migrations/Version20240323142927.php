<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240323142927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieux_ventes DROP FOREIGN KEY FK_8FE8FCB74AF6CB4E');
        $this->addSql('DROP INDEX IDX_8FE8FCB74AF6CB4E ON lieux_ventes');
        $this->addSql('ALTER TABLE lieux_ventes CHANGE enentreprise_id entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE lieux_ventes ADD CONSTRAINT FK_8FE8FCB7A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('CREATE INDEX IDX_8FE8FCB7A4AEAFEA ON lieux_ventes (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieux_ventes DROP FOREIGN KEY FK_8FE8FCB7A4AEAFEA');
        $this->addSql('DROP INDEX IDX_8FE8FCB7A4AEAFEA ON lieux_ventes');
        $this->addSql('ALTER TABLE lieux_ventes CHANGE entreprise_id enentreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE lieux_ventes ADD CONSTRAINT FK_8FE8FCB74AF6CB4E FOREIGN KEY (enentreprise_id) REFERENCES entreprise (id)');
        $this->addSql('CREATE INDEX IDX_8FE8FCB74AF6CB4E ON lieux_ventes (enentreprise_id)');
    }
}
