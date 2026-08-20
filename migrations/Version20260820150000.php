<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260820150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add readable order numbers and default currency snapshots.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shop_order ADD order_number VARCHAR(24) DEFAULT NULL, ADD default_currency_code VARCHAR(3) DEFAULT NULL');
        $this->addSql("UPDATE shop_order SET order_number = CONCAT('SH-', DATE_FORMAT(created_at, '%Y%m%d'), '-', UPPER(RIGHT(HEX(id), 8))) WHERE order_number IS NULL");
        $this->addSql("UPDATE shop_order SET default_currency_code = (SELECT code FROM currency WHERE is_default = 1 LIMIT 1) WHERE default_currency_code IS NULL");
        $this->addSql('ALTER TABLE shop_order MODIFY order_number VARCHAR(24) NOT NULL, MODIFY default_currency_code VARCHAR(3) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4D5D2B5F7E5D3E7D ON shop_order (order_number)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_4D5D2B5F7E5D3E7D ON shop_order');
        $this->addSql('ALTER TABLE shop_order DROP order_number, DROP default_currency_code');
    }
}
