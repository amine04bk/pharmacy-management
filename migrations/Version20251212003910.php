<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251212003910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medicines (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, category VARCHAR(100) NOT NULL, quantity INT NOT NULL, price NUMERIC(10, 2) NOT NULL, expiration_date DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, order_date DATETIME NOT NULL, status VARCHAR(50) NOT NULL, medicine_id INT NOT NULL, supplier_id INT NOT NULL, INDEX IDX_E52FFDEE2F7D140A (medicine_id), INDEX IDX_E52FFDEE2ADD6D8C (supplier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pharmacy_inventory (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, purchase_price NUMERIC(10, 2) NOT NULL, last_restocked DATETIME NOT NULL, pharmacy_id INT NOT NULL, medicine_id INT NOT NULL, INDEX IDX_2FAF99C48A94ABE2 (pharmacy_id), INDEX IDX_2FAF99C42F7D140A (medicine_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE supplier_inventory (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, wholesale_price NUMERIC(10, 2) NOT NULL, last_restocked DATETIME NOT NULL, supplier_id INT NOT NULL, medicine_id INT NOT NULL, INDEX IDX_2B1F70E82ADD6D8C (supplier_id), INDEX IDX_2B1F70E82F7D140A (medicine_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE suppliers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, postal_code VARCHAR(20) DEFAULT NULL, is_active TINYINT NOT NULL, created_at DATETIME NOT NULL, supplier_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E92ADD6D8C (supplier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE2F7D140A FOREIGN KEY (medicine_id) REFERENCES medicines (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id)');
        $this->addSql('ALTER TABLE pharmacy_inventory ADD CONSTRAINT FK_2FAF99C48A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pharmacy_inventory ADD CONSTRAINT FK_2FAF99C42F7D140A FOREIGN KEY (medicine_id) REFERENCES medicines (id)');
        $this->addSql('ALTER TABLE supplier_inventory ADD CONSTRAINT FK_2B1F70E82ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id)');
        $this->addSql('ALTER TABLE supplier_inventory ADD CONSTRAINT FK_2B1F70E82F7D140A FOREIGN KEY (medicine_id) REFERENCES medicines (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE2F7D140A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE2ADD6D8C');
        $this->addSql('ALTER TABLE pharmacy_inventory DROP FOREIGN KEY FK_2FAF99C48A94ABE2');
        $this->addSql('ALTER TABLE pharmacy_inventory DROP FOREIGN KEY FK_2FAF99C42F7D140A');
        $this->addSql('ALTER TABLE supplier_inventory DROP FOREIGN KEY FK_2B1F70E82ADD6D8C');
        $this->addSql('ALTER TABLE supplier_inventory DROP FOREIGN KEY FK_2B1F70E82F7D140A');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E92ADD6D8C');
        $this->addSql('DROP TABLE medicines');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE pharmacy_inventory');
        $this->addSql('DROP TABLE supplier_inventory');
        $this->addSql('DROP TABLE suppliers');
        $this->addSql('DROP TABLE users');
    }
}
