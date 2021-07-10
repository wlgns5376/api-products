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

    }

    /**
     * Product store test
     * 
     * @return void
     */
    public function test_stored_product()
    {
        $product = Product::factory()->make();
        // Price numeric test
        $response = $this->postJson('/api/products', [
            'name' => $product->name,
            'price' => $product->price,
        ]);

        $response->assertStatus(201);
        $this->assertEquals($response['price'], $product->price);
        $this->assertNotEmpty($response['id']);
    }
}
