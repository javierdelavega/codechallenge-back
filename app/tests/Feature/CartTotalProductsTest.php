<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

class CartTotalProductsTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test calculate the total price of the cart with one product
     */
    public function test_calculate_total_of_single_product(): void
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
        ])->get('/api/cart/products/total');

        $response
            ->assertStatus(200)
            ->assertJson([
                'total' => $product->price * $quantity,
            ]);
    }
    
    /**
     * Test calculate the total price of the cart with multiple products
     */
    public function test_calculate_total_of_multiple_products(): void
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
        ])->get('/api/cart/products/total');

        $response
            ->assertStatus(200)
            ->assertJson([
                'total' => $product1->price * $quantity1 + $product2->price * $quantity2,
            ]);
    }
    

}
