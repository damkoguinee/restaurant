<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408082546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse ADD cloture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFE3FE4D754 FOREIGN KEY (cloture_id) REFERENCES cloture_caisse (id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFE3FE4D754 ON mouvement_caisse (cloture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFE3FE4D754');
        $this->addSql('DROP INDEX IDX_C8E3DDFE3FE4D754 ON mouvement_caisse');
        $this->addSql('ALTER TABLE mouvement_caisse DROP cloture_id');
    }
}
