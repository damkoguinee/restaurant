<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324131105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_plat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plat ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207C54C8C93 FOREIGN KEY (type_id) REFERENCES type_plat (id)');
        $this->addSql('CREATE INDEX IDX_2038A207C54C8C93 ON plat (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207C54C8C93');
        $this->addSql('DROP TABLE type_plat');
        $this->addSql('DROP INDEX IDX_2038A207C54C8C93 ON plat');
        $this->addSql('ALTER TABLE plat DROP type_id');
    }
}
