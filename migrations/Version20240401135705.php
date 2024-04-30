<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401135705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BAEE4462DB');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BAEE4462DB FOREIGN KEY (achat_fournisseur_id) REFERENCES liste_product_achat_fournisseur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BAEE4462DB');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BAEE4462DB FOREIGN KEY (achat_fournisseur_id) REFERENCES achat_fournisseur (id)');
    }
}
