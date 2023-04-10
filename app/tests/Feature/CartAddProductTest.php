<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartAddProductTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test add valid product (existing in DB) to the cart
     */
    public function test_can_add_new_product_to_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => '1',
        ]);

        $response->assertStatus(200);
    }
    

    /**
     * Test add invalid product (not existing in DB) to the cart
     */
    public function test_can_not_add_invalid_product_to_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => 12345,
            'quantity' => '1',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test add product with zero quantity
     */
    public function test_can_not_add_product_with_zero_quantity_to_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => '0',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test add product with negative quantity
     */
    public function test_can_not_add_product_with_negative_quantity_to_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => '-1',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test add product already existing in the cart
     */
    public function test_can_add_already_existing_product_in_the_cart(): void
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
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => '2',
        ]);

        $response->assertStatus(200);
    }


}
