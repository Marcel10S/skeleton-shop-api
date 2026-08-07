<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807205000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add delivery details for orders.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE shop_order_delivery (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', order_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', courier VARCHAR(20) NOT NULL, recipient_name VARCHAR(255) NOT NULL, address_line VARCHAR(255) NOT NULL, postal_code VARCHAR(20) NOT NULL, city VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_AE8E4D198D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_order_delivery ADD CONSTRAINT FK_AE8E4D198D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shop_order_delivery DROP FOREIGN KEY FK_AE8E4D198D9F6D38');
        $this->addSql('DROP TABLE shop_order_delivery');
    }
}
