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
        $client->request('POST', '/api/products', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
            'description' => 'Test description'
        ]));

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseFormatSame('json');
    }

    public function testGetSingleProduct(): void
    {
        $client = static::createClient();
        // First, create a product
        $client->request('POST', '/api/products', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Single Product',
            'price' => 49.99,
            'stock' => 5,
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);
        $id = $data['id'] ?? null;

        $this->assertNotNull($id, 'Product ID should not be null');

        // Then, fetch the same product
        $client->request('GET', '/api/products/' . $id);
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testUpdateProduct(): void
    {
        $client = static::createClient();
        // Create a product first
        $client->request('POST', '/api/products', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Update Product',
            'price' => 19.99,
            'stock' => 3,
        ]));
        $data = json_decode($client->getResponse()->getContent(), true);
        $id = $data['id'] ?? null;

        $this->assertNotNull($id);

        // Update it
        $client->request('PUT', '/api/products/' . $id, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Updated Product',
            'price' => 29.99,
            'stock' => 4,
            'description' => 'Updated desc'
        ]));

        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('Updated Product', $response['name']);
    }

    public function testDeleteProduct(): void
    {
        $client = static::createClient();
        // Create a product
        $client->request('POST', '/api/products', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Delete Me',
            'price' => 9.99,
            'stock' => 2,
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);
        $id = $data['id'] ?? null;

        $this->assertNotNull($id);

        // Delete it
        $client->request('DELETE', '/api/products/' . $id);
        $this->assertResponseStatusCodeSame(204);
    }
}
