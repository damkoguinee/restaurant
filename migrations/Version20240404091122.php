<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404091122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liaison_product DROP FOREIGN KEY FK_85A0C14B4F22BEF1');
        $this->addSql('ALTER TABLE liaison_product DROP FOREIGN KEY FK_85A0C14B5D97111F');
        $this->addSql('ALTER TABLE liaison_product ADD CONSTRAINT FK_85A0C14B4F22BEF1 FOREIGN KEY (product2_id) REFERENCES boisson (id)');
        $this->addSql('ALTER TABLE liaison_product ADD CONSTRAINT FK_85A0C14B5D97111F FOREIGN KEY (product1_id) REFERENCES boisson (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liaison_product DROP FOREIGN KEY FK_85A0C14B5D97111F');
        $this->addSql('ALTER TABLE liaison_product DROP FOREIGN KEY FK_85A0C14B4F22BEF1');
        $this->addSql('ALTER TABLE liaison_product ADD CONSTRAINT FK_85A0C14B5D97111F FOREIGN KEY (product1_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE liaison_product ADD CONSTRAINT FK_85A0C14B4F22BEF1 FOREIGN KEY (product2_id) REFERENCES product (id)');
    }
}
