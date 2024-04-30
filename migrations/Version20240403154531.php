<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403154531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_product ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BA82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande_product (id)');
        $this->addSql('CREATE INDEX IDX_1C8AD1BA82EA2E54 ON mouvement_product (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BA82EA2E54');
        $this->addSql('DROP INDEX IDX_1C8AD1BA82EA2E54 ON mouvement_product');
        $this->addSql('ALTER TABLE mouvement_product DROP commande_id');
    }
}
