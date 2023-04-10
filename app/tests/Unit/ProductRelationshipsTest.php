<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductRelationshipsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** 
     * @test
     * Test BelongsToMany carts relationship not yet used in features
     */
    public function a_product_belongs_to_many_carts()
    {
        $product = Product::factory()->make();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->carts); 
    }

    /** 
     * @test
     * Test BelongsToMany orders relationship not yet used in features
     */
    public function a_product_belongs_to_many_orders()
    {
        $product = Product::factory()->make();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->orders); 
    }
}
