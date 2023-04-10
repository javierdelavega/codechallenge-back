<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CartEmptyTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test empty the cart (delete all the product in the cart)
     */
    public function test_can_empy_the_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('/api/cart/products');

        $response->assertStatus(200);
    }

}
