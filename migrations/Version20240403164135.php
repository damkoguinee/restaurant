<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403164135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390933FE08C');
        $this->addSql('ALTER TABLE recette ADD product_id INT NOT NULL, CHANGE ingredient_id ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB63904584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390933FE08C FOREIGN KEY (ingredient_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_49BB63904584665A ON recette (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB63904584665A');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390933FE08C');
        $this->addSql('DROP INDEX IDX_49BB63904584665A ON recette');
        $this->addSql('ALTER TABLE recette DROP product_id, CHANGE ingredient_id ingredient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
    }
}
