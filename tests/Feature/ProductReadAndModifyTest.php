<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductReadAndModifyTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * Product read test
     *
     * @return void
     */
    public function test_read_and_modify_product()
    {
        // Read test
        $product = Product::factory()->create();
        $url = '/api/products/'.$product->id;
        $response = $this->getJson($url);

        $response->assertStatus(200);
        $this->assertEquals($product->id, $response['id']);

        // Modify test
        $newProduct = Product::factory()->make();
        $response = $this->putJson($url, [
            'name' => $newProduct->name,
            'price' => $newProduct->price,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $product->id,
                    'name' => $newProduct->name,
                    'price' => $newProduct->price,
                ]);
    }

    /**
     * Non exists product test
     * 
     * @return void
     */
    public function test_non_exists_product()
    {
        $url = '/api/products/1';
        // read
        $response = $this->getJson($url);
        $response->assertStatus(404);

        // modify
        $response = $this->putJson($url, [
            'name' => 'Test product name',
            'price' => 0.99,
        ]);
        $response->assertStatus(404);
    }

    /**
     * Validate test
     * 
     * @return void
     */
    public function test_validate_modifed_product()
    {
        $product = Product::factory()->create();
        $response = $this->putJson('/api/products/'.$product->id, [
            'price' => 'price test',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'price' => 'number',
                ]);
    }
}
