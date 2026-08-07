<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807202000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add orders and immutable order-item price snapshots.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE shop_order (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_order_item (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', order_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', product_name VARCHAR(255) NOT NULL, quantity INT NOT NULL, unit_amount INT NOT NULL, currency VARCHAR(3) NOT NULL, INDEX IDX_C1D5B8878D9F6D38 (order_id), INDEX IDX_C1D5B8874584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_C1D5B8878D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_C1D5B8874584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_C1D5B8878D9F6D38');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_C1D5B8874584665A');
        $this->addSql('DROP TABLE shop_order_item');
        $this->addSql('DROP TABLE shop_order');
    }
}
