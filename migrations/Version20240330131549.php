<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240330131549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dessert (id INT NOT NULL, etat VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entree (id INT NOT NULL, etat VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dessert ADD CONSTRAINT FK_79291B96BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entree ADD CONSTRAINT FK_598377A6BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_dessert DROP FOREIGN KEY FK_608F0B35745B52FD');
        $this->addSql('ALTER TABLE dessert DROP FOREIGN KEY FK_79291B96BF396750');
        $this->addSql('ALTER TABLE entree DROP FOREIGN KEY FK_598377A6BF396750');
        $this->addSql('DROP TABLE dessert');
        $this->addSql('DROP TABLE entree');
    }
}
