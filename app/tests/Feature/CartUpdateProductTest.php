<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CartUpdateProductTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test update existing product in the cart
     */
    public function test_can_update_existing_product(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => '1',
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/api/cart/product/'.$product->id, [
            'id' => $product->id,
            'quantity' => '1',
        ]);

        $response->assertStatus(200);
    }
    

    /**
     * Test update not existing product in the cart
     */
    public function test_can_not_update_not_existing_product(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $inexisting_product_id = 12345;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/api/cart/product/'.$inexisting_product_id, [
            'id' => $inexisting_product_id,
            'quantity' => '1',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test update existing product with zero quantity
     */
    public function test_can_not_update_existing_product_with_zero_quantity(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => '1',
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/api/cart/product/'.$product->id, [
            'id' => $product->id,
            'quantity' => '0',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test update existing product with negative quantity
     */
    public function test_can_not_update_existing_product_with_negative_quantity(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => '1',
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/api/cart/product/'.$product->id, [
            'id' => $product->id,
            'quantity' => '-1',
        ]);

        $response->assertStatus(422);
    }

}
