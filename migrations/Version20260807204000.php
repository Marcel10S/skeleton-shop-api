<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807204000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Use a configurable default currency instead of PLN as the exchange-rate base.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE currency CHANGE rate_to_pln rate_to_default_currency INT NOT NULL, ADD is_default TINYINT(1) NOT NULL DEFAULT 0');
        $this->addSql("UPDATE currency SET is_default = 1 WHERE code = 'PLN'");
        $this->addSql('ALTER TABLE shop_order_item CHANGE rate_to_pln rate_to_default_currency INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shop_order_item CHANGE rate_to_default_currency rate_to_pln INT NOT NULL');
        $this->addSql('ALTER TABLE currency DROP is_default, CHANGE rate_to_default_currency rate_to_pln INT NOT NULL');
    }
}
