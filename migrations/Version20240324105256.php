<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324105256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(20) NOT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE type_user type_user VARCHAR(50) NOT NULL, CHANGE pays pays VARCHAR(150) NOT NULL, CHANGE statut statut VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649f85e0677 TO UNIQ_IDENTIFIER_USERNAME');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE email email VARCHAR(150) DEFAULT NULL, CHANGE type_user type_user VARCHAR(15) NOT NULL, CHANGE pays pays VARCHAR(100) NOT NULL, CHANGE statut statut VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_username TO UNIQ_8D93D649F85E0677');
    }
}
