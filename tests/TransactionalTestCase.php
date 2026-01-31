<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\DBAL\Connection;

abstract class TransactionalTestCase extends WebTestCase
{
    protected ?Connection $connection = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function beginTransaction(): void
    {
        $this->connection = self::getContainer()
            ->get('doctrine')
            ->getConnection();

        if (!$this->connection->isTransactionActive()) {
            $this->connection->beginTransaction();
        }
    }

    protected function tearDown(): void
    {
        if ($this->connection?->isTransactionActive()) {
            $this->connection->rollBack();
        }

        parent::tearDown();
    }
}
