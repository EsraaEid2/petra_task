<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
    public function test_can_create_product()
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/products', [
            'title' => 'Test Product',
            'description' => 'Test description',
            'price' => 100.00,
            'category_id' => $category->id,
            'stock_quantity' => 10,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message', 'product' => ['id', 'title', 'price']
        ]);
    }

}