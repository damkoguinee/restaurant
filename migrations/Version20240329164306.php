<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329164306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cocktail ADD type_cocktail_id INT NOT NULL');
        $this->addSql('ALTER TABLE cocktail ADD CONSTRAINT FK_7B4914D48676A157 FOREIGN KEY (type_cocktail_id) REFERENCES type_cocktail (id)');
        $this->addSql('CREATE INDEX IDX_7B4914D48676A157 ON cocktail (type_cocktail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cocktail DROP FOREIGN KEY FK_7B4914D48676A157');
        $this->addSql('DROP INDEX IDX_7B4914D48676A157 ON cocktail');
        $this->addSql('ALTER TABLE cocktail DROP type_cocktail_id');
    }
}
