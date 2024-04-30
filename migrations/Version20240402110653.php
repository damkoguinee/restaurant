<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402110653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense ADD lieu_vente_id INT NOT NULL');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_34059757AA2B41DC ON depense (lieu_vente_id)');
        $this->addSql('ALTER TABLE stock_plat CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE stock_plat ADD CONSTRAINT FK_3922FAC3BF396750 FOREIGN KEY (id) REFERENCES stock (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757AA2B41DC');
        $this->addSql('DROP INDEX IDX_34059757AA2B41DC ON depense');
        $this->addSql('ALTER TABLE depense DROP lieu_vente_id');
        $this->addSql('ALTER TABLE stock_plat DROP FOREIGN KEY FK_3922FAC3BF396750');
        $this->addSql('ALTER TABLE stock_plat CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
