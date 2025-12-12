<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251212010403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY `FK_E52FFDEE2ADD6D8C`');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY `FK_E52FFDEE2F7D140A`');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE2F7D140A FOREIGN KEY (medicine_id) REFERENCES medicines (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pharmacy_inventory DROP FOREIGN KEY `FK_2FAF99C42F7D140A`');
        $this->addSql('ALTER TABLE pharmacy_inventory DROP FOREIGN KEY `FK_2FAF99C48A94ABE2`');
        $this->addSql('ALTER TABLE pharmacy_inventory ADD CONSTRAINT FK_2FAF99C42F7D140A FOREIGN KEY (medicine_id) REFERENCES medicines (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pharmacy_inventory ADD CONSTRAINT FK_2FAF99C48A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_inventory DROP FOREIGN KEY `FK_2B1F70E82ADD6D8C`');
        $this->addSql('ALTER TABLE supplier_inventory DROP FOREIGN KEY `FK_2B1F70E82F7D140A`');
        $this->addSql('ALTER TABLE supplier_inventory ADD CONSTRAINT FK_2B1F70E82ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_inventory ADD CONSTRAINT FK_2B1F70E82F7D140A FOREIGN KEY (medicine_id) REFERENCES medicines (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE2F7D140A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE2ADD6D8C');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT `FK_E52FFDEE2F7D140A` FOREIGN KEY (medicine_id) REFERENCES medicines (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT `FK_E52FFDEE2ADD6D8C` FOREIGN KEY (supplier_id) REFERENCES suppliers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE pharmacy_inventory DROP FOREIGN KEY FK_2FAF99C48A94ABE2');
        $this->addSql('ALTER TABLE pharmacy_inventory DROP FOREIGN KEY FK_2FAF99C42F7D140A');
        $this->addSql('ALTER TABLE pharmacy_inventory ADD CONSTRAINT `FK_2FAF99C48A94ABE2` FOREIGN KEY (pharmacy_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE pharmacy_inventory ADD CONSTRAINT `FK_2FAF99C42F7D140A` FOREIGN KEY (medicine_id) REFERENCES medicines (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE supplier_inventory DROP FOREIGN KEY FK_2B1F70E82ADD6D8C');
        $this->addSql('ALTER TABLE supplier_inventory DROP FOREIGN KEY FK_2B1F70E82F7D140A');
        $this->addSql('ALTER TABLE supplier_inventory ADD CONSTRAINT `FK_2B1F70E82ADD6D8C` FOREIGN KEY (supplier_id) REFERENCES suppliers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE supplier_inventory ADD CONSTRAINT `FK_2B1F70E82F7D140A` FOREIGN KEY (medicine_id) REFERENCES medicines (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
