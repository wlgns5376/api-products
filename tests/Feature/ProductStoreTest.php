<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Validate test
     *
     * @return void
     */
    public function test_stored_validate()
    {
        $response = $this->postJson('/api/products');

        // $response->dump();
        // Required test
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name' => 'required',
            'price' => 'required',
        ]);

        $product = Product::factory()->make();
        // Price numeric test
        $response = $this->postJson('/api/products', [
            'name' => $product->name,
            'price' => 'is not numeric',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'price' => 'number',
        ]);

        // Stock integer test
        $response = $this->postJson('/api/products', [
            'name' => $product->name,
            'price' => $product->price,
            'stock' => 1.99,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'stock' => 'integer',
        ]);

        // Stock min test
        $response = $this->postJson('/api/products', [
            'name' => $product->name,
            'price' => $product->price,
            'stock' => -1,
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'stock' => 'least 0',
                ]);

    }

    /**
     * Product store test
     * 
     * @return void
     */
    public function test_stored_product()
    {
        $product = Product::factory()->make();
        $response = $this->postJson('/api/products', [
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
        ]);

        $response->assertStatus(201);
        $this->assertEquals($response['price'], $product->price);
        $this->assertNotEmpty($response['id']);
    }

    /**
     * Product store stock null test
     * 
     * @return void
     */
    public function test_stored_null_stock_product()
    {
        $product = Product::factory()->make();
        $response = $this->postJson('/api/products', [
            'name' => $product->name,
            'price' => $product->price,
        ]);

        $response->assertStatus(201);
        $this->assertNull($response['stock']);
    }

    /**
     * Is unlimited stock test
     * 
     * @return void
     */
    public function test_is_unlimited_stock()
    {
        $product = Product::factory()->create([
            'stock' => null
        ]);

        $this->assertTrue($product->isUnlimitedStock());

        $product->stock = 0;
        $this->assertFalse($product->isUnlimitedStock());
    }
}
