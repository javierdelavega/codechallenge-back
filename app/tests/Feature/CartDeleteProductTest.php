<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CartDeleteProductTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test delete existing product in the cart
     */
    public function test_can_delete_existing_product(): void
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
        ])->delete('/api/cart/product/'.$product->id, []);

        $response->assertStatus(200);
    }
    

    /**
     * Test delete not existing product in the cart
     */
    public function test_can_not_delete_not_existing_product(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $inexisting_product_id = 12345;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('/api/cart/product/'.$inexisting_product_id, []);

        $response->assertStatus(404);
    }

}
