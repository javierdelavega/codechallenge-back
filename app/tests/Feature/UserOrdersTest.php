<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;

class UserOrdersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if can get the orders of the user
     */
    public function test_can_get_orders_of_current_user(): void
    {
        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $product = Product::factory()->create();
        
        $user->cart->products()->attach($product->id, ['quantity' => 1]);
        $order = Order::create([
            'user_id' => $user->id,
            'address' => $user->address,
            'total' => $product->price,
        ]);

        foreach($user->cart->products as $product) {
            $order->products()->attach($product->id, [
                'quantity' => $product->pivot->quantity, 
                'price' => $product->price,
            ]);
        }

        $response = $this->get('/api/user/orders');

        $response
            ->assertStatus(200)->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->has('orders', 1, fn ($json) =>
                    $json->where('id', $order->id)
                    ->where('user_id', $order->user->id)
                    ->where('address', $order->address)
                    ->where('total', $order->total)
                    ->etc()
                 )
        );
    }

    
}
