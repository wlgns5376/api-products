<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductDestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Destroy product test
     *
     * @return void
     */
    public function test_destroied_product()
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson('/api/products/'.$product->id);
        $response->assertStatus(200);
    }

    /**
     * Non exists destroied product
     */
    public function test_non_exists_product()
    {
        $response = $this->deleteJson('/api/products/1');
        $response->assertStatus(404);
    }
}
