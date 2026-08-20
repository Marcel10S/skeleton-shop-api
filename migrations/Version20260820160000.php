<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260820160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add product priority for storefront ranking.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD priority INT NOT NULL DEFAULT 1');
        $this->addSql('CREATE INDEX IDX_D34A04AD3B7D4B7A ON product (priority)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_D34A04AD3B7D4B7A ON product');
        $this->addSql('ALTER TABLE product DROP priority');
    }
}
