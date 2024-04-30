<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401114102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chicha_recette (id INT NOT NULL, chicha_id INT NOT NULL, INDEX IDX_D8C3F041A37FD6CB (chicha_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cocktail_recette (id INT NOT NULL, cocktail_id INT NOT NULL, boisson_id INT DEFAULT NULL, INDEX IDX_8A60C8ACCD6F76C6 (cocktail_id), INDEX IDX_8A60C8AC734B8089 (boisson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat_recette (id INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_CEEAE377D73DB560 (plat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, ingredient_id INT NOT NULL, quantite DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_49BB6390933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chicha_recette ADD CONSTRAINT FK_D8C3F041A37FD6CB FOREIGN KEY (chicha_id) REFERENCES chicha (id)');
        $this->addSql('ALTER TABLE chicha_recette ADD CONSTRAINT FK_D8C3F041BF396750 FOREIGN KEY (id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cocktail_recette ADD CONSTRAINT FK_8A60C8ACCD6F76C6 FOREIGN KEY (cocktail_id) REFERENCES cocktail (id)');
        $this->addSql('ALTER TABLE cocktail_recette ADD CONSTRAINT FK_8A60C8AC734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id)');
        $this->addSql('ALTER TABLE cocktail_recette ADD CONSTRAINT FK_8A60C8ACBF396750 FOREIGN KEY (id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat_recette ADD CONSTRAINT FK_CEEAE377D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE plat_recette ADD CONSTRAINT FK_CEEAE377BF396750 FOREIGN KEY (id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chicha_recette DROP FOREIGN KEY FK_D8C3F041A37FD6CB');
        $this->addSql('ALTER TABLE chicha_recette DROP FOREIGN KEY FK_D8C3F041BF396750');
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8ACCD6F76C6');
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8AC734B8089');
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8ACBF396750');
        $this->addSql('ALTER TABLE plat_recette DROP FOREIGN KEY FK_CEEAE377D73DB560');
        $this->addSql('ALTER TABLE plat_recette DROP FOREIGN KEY FK_CEEAE377BF396750');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390933FE08C');
        $this->addSql('DROP TABLE chicha_recette');
        $this->addSql('DROP TABLE cocktail_recette');
        $this->addSql('DROP TABLE plat_recette');
        $this->addSql('DROP TABLE recette');
    }
}
