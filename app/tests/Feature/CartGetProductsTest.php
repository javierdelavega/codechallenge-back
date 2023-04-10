<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartGetProductsTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test get the content of the cart
     */
    public function test_can_get_content_of_the_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/cart/products');

        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['products', 'count', 'total'])
        );
    }


}
