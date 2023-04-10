<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;


class CartCountProductsTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test count total quantity with one product in the cart
     */
    public function test_count_quantity_with_single_product(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();
        $quantity = 5;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product->id,
            'quantity' => $quantity,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/cart/products/count');

        $response
            ->assertStatus(200)
            ->assertJson([
                'count' => $quantity,
            ]);
    }
    
    /**
     * Test count total quantity with multiple products in the cart
     */
    public function test_count_quantity_of_multiple_products(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product1 = Product::factory()->create();
        $quantity1 = 5;
        $product2 = Product::factory()->create();
        $quantity2 = 20;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product1->id,
            'quantity' => $quantity1,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/product', [
            'id' => $product2->id,
            'quantity' => $quantity2,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/cart/products/count');

        $response
            ->assertStatus(200)
            ->assertJson([
                'count' => $quantity1 + $quantity2,
            ]);
    }
    

}
