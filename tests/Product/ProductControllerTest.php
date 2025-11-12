<?php

namespace App\tests\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testListProducts(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testCreateProduct(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Product',
                'amount' => 9999,
                'currency' => 'PLN',
                'stock' => 10,
                'description' => 'Test description'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('Test Product', $data['name']);
        $this->assertEquals('PLN', $data['price']['currency']);
        $this->assertEquals(9999, $data['price']['amount']);
        $this->assertEquals(10, $data['stock']);
        $this->assertEquals('Test description', $data['description']);
    }

    public function testGetSingleProduct(): void
    {
        $client = static::createClient();

        // Create product first
        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Product',
                'amount' => 9999,
                'currency' => 'PLN',
                'stock' => 10,
                'description' => 'Test description'
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $id = $data['id'] ?? null;

        $this->assertNotNull($id, 'Product ID should not be null');

        // Then fetch it
        $client->request('GET', '/api/products/' . $id);
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('Test Product', $data['name']);
        $this->assertEquals('PLN', $data['price']['currency']);
        $this->assertEquals(9999, $data['price']['amount']);
        $this->assertEquals(10, $data['stock']);
        $this->assertEquals('Test description', $data['description']);
    }

    public function testUpdateProduct(): void
    {
        $client = static::createClient();

        // Create product
        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Product',
                'amount' => 9999,
                'currency' => 'PLN',
                'stock' => 10,
                'description' => 'Test description'
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $id = $data['id'] ?? null;
        $this->assertNotNull($id);

        // Update product
        $client->request(
            'PUT',
            '/api/products/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Product II',
                'amount' => 10000,
                'currency' => 'EUR',
                'stock' => 9,
                'description' => 'Test description II'
            ])
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('Test Product II', $response['name']);
        $this->assertEquals('EUR', $response['price']['currency']);
        $this->assertEquals(10000, $response['price']['amount']);
        $this->assertEquals(9, $response['stock']);
        $this->assertEquals('Test description II', $response['description']);
    }

    public function testDeleteProduct(): void
    {
        $client = static::createClient();

        // Create product
        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Product',
                'amount' => 9999,
                'currency' => 'PLN',
                'stock' => 10,
                'description' => 'Test description'
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $id = $data['id'] ?? null;
        $this->assertNotNull($id);

        // Delete product
        $client->request('DELETE', '/api/products/' . $id);
        $this->assertResponseStatusCodeSame(204);
    }
}
