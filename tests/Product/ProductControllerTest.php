<?php

namespace App\Tests\Product;

use App\Entity\App\Product;
use App\Entity\Embeddable\Money;
use App\Tests\TransactionalTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProductControllerTest extends TransactionalTestCase
{
    private EntityManagerInterface $em;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->disableReboot();

        $this->beginTransaction();

        $this->em = self::getContainer()->get('doctrine')->getManager();
        $this->em->createQuery('DELETE FROM App\Entity\App\Product p')->execute();

        for ($i = 1; $i <= 3; $i++) {
            $product = new Product(
                name: 'Mock Product ' . $i,
                stock: 10 * $i,
                price: new Money(1000 * $i, 'PLN')
            );

            $product->setDescription('Mock description ' . $i);
            $product->setIsActive(true);

            $this->em->persist($product);
        }

        $this->em->flush();
    }

    public function testListProducts(): void
    {
        $this->client->disableReboot();
        $this->client->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(3, $data);
        $this->assertSame('Mock Product 1', $data[0]['name']);
    }

    public function testCreateProduct(): void
    {
        $this->client->disableReboot();
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Created Product',
                'amount' => 9999,
                'currency' => 'PLN',
                'stock' => 5,
                'description' => 'Created from test',
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseFormatSame('json');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $id = $responseData['id'] ?? null;

        $this->assertNotNull($id);

        $product = $this->em->getRepository(Product::class)->find($id);

        $this->assertNotNull($product);
        $this->assertSame('Created Product', $product->getName());
        $this->assertSame(5, $product->getStock());
        $this->assertSame('Created from test', $product->getDescription());
        $this->assertSame(9999, $product->getPrice()->getAmount());
        $this->assertSame('PLN', $product->getPrice()->getCurrency());
        $this->assertTrue($product->isActive());
    }

    public function testGetSingleProduct(): void
    {
        $this->client->disableReboot();
        $product = $this->createProduct('Single Product');

        $this->client->request('GET', '/api/products/' . $product->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('Single Product', $data['name']);
    }

    public function testUpdateProduct(): void
    {
        $this->client->disableReboot();
        $product = $this->createProduct();

        $this->client->request(
            'PUT',
            '/api/products/' . $product->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Updated Product',
                'amount' => 20000,
                'currency' => 'EUR',
                'stock' => 3,
                'description' => 'Updated description',
            ])
        );

        $this->assertResponseIsSuccessful();

        $this->em->clear();
        $updated = $this->em->getRepository(Product::class)->find($product->getId());

        $this->assertSame('Updated Product', $updated->getName());
        $this->assertSame(3, $updated->getStock());
        $this->assertSame(20000, $updated->getPrice()->getAmount());
        $this->assertSame('EUR', $updated->getPrice()->getCurrency());
    }

    public function testDeleteProduct(): void
    {
        $this->client->disableReboot();

        $product = $this->createProduct();

        $this->client->request('DELETE', '/api/products/' . $product->getId());
        $this->assertResponseStatusCodeSame(204);

        $this->em->clear();
        $deleted = $this->em->getRepository(Product::class)->find($product->getId());

        $this->assertNull($deleted);
    }

    private function createProduct(string $name = 'Test Product'): Product
    {
        $product = new Product(
            name: $name,
            stock: 10,
            price: new Money(9999, 'PLN')
        );

        $product->setDescription('Test description');
        $product->setIsActive(true);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }
}
