<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    // Undo all the changes in the DB after run the tests
    use RefreshDatabase;

    /**
     * Get products list from store
     */
    public function test_can_get_list_of_products(): void
    {
        Sanctum::actingAs(User::factory()->create());
        
        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }
    /**
     * Get a product by id from the store
     */
    public function test_can_get_a_single_product(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->get('/api/product/'.$product->id);

        $response->assertStatus(200);
    }

}
