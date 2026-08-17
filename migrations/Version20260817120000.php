<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260817120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add payment methods and payment status to orders.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment_method (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(50) NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_42D643C577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("INSERT INTO payment_method (id, code, name) VALUES (UNHEX(REPLACE('0198e26c-088f-7f2c-a1a8-2e469d18e197', '-', '')), 'cash_on_delivery', 'Cash on delivery'), (UNHEX(REPLACE('0198e26c-088f-7f64-9e11-3f413d93dd86', '-', '')), 'bank_transfer', 'Bank transfer')");
        $this->addSql('ALTER TABLE shop_order ADD payment_method_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD payment_status VARCHAR(20) NOT NULL DEFAULT \'pending\'');
        $this->addSql('UPDATE shop_order SET payment_method_id = UNHEX(REPLACE(\'0198e26c-088f-7f2c-a1a8-2e469d18e197\', \'-\', \'\'))');
        $this->addSql('ALTER TABLE shop_order MODIFY payment_method_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_F5A1A5056CF6D63 FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('CREATE INDEX IDX_F5A1A5056CF6D63 ON shop_order (payment_method_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_F5A1A5056CF6D63');
        $this->addSql('DROP INDEX IDX_F5A1A5056CF6D63 ON shop_order');
        $this->addSql('ALTER TABLE shop_order DROP payment_method_id, DROP payment_status');
        $this->addSql('DROP TABLE payment_method');
    }
}
