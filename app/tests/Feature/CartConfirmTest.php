<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartConfirmTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test registered users can confirm the cart
     */
    public function test_can_confirm_the_cart(): void
    {
        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $product = Product::factory()->create();
        
        $user->cart->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/confirm');
        
        $response->assertStatus(200);
    }
    

    /**
     * Test guest users can not confirm the cart
     */
    public function test_guest_user_can_not_confirm_the_cart(): void
    {
        $user = User::factory()->guest()->create();
        Cart::create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $product = Product::factory()->create();
        
        $user->cart->products()->attach($product->id, ['quantity' => 1]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/confirm');
        
        $response->assertStatus(403);
    }

    /**
     * Test can not confirm empty cart
     */
    public function test_can_not_confirm_empty_cart(): void
    {
        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/cart/confirm');
        
        $response->assertStatus(403);
    }

}
