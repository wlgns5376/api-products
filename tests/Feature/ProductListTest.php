<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Products list test
     *
     * @return void
     */
    public function test_product_index()
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);

    }

    /**
     * Paginate test
     * 
     * @return void
     */
    public function test_paginate()
    {
        Product::factory()->count(20)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $this->assertTrue($response['last_page'] === 2);

        $response = $this->getJson('/api/products?page=2');
        $response->assertStatus(200);
        $this->assertTrue(count($response['data']) === 5);
    }

}
