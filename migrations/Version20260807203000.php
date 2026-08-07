<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807203000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add configurable exchange rates and PLN rate snapshots for order items.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE currency (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(3) NOT NULL, name VARCHAR(100) NOT NULL, rate_to_pln INT NOT NULL, UNIQUE INDEX UNIQ_6956883E77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("INSERT INTO currency (id, code, name, rate_to_pln) VALUES (UNHEX(REPLACE('0198e26c-088f-7e22-8387-049c1f3ec8f9', '-', '')), 'PLN', 'Polski złoty', 100), (UNHEX(REPLACE('0198e26c-088f-7eb7-99e5-b9cc0be36b7c', '-', '')), 'EUR', 'Euro', 480), (UNHEX(REPLACE('0198e26c-088f-7f1b-a8ed-7a413d92fe3b', '-', '')), 'USD', 'Dolar amerykański', 400)");
        $this->addSql('ALTER TABLE shop_order_item ADD rate_to_pln INT NOT NULL DEFAULT 100');
        $this->addSql("UPDATE shop_order_item SET rate_to_pln = CASE currency WHEN 'EUR' THEN 480 WHEN 'USD' THEN 400 ELSE 100 END");
        $this->addSql('ALTER TABLE shop_order_item ALTER rate_to_pln DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shop_order_item DROP rate_to_pln');
        $this->addSql('DROP TABLE currency');
    }
}
