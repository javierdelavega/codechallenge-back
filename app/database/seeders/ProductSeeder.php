<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
        	['reference' => 'SKU00001', 'name' => 'MUNDAKA', 'description' => 'Gafas de sol MUNDAKA', 'price' => '35'],
            ['reference' => 'SKU00002', 'name' => 'MACBA', 'description' => 'Gafas de sol MACBA', 'price' => '35'],
            ['reference' => 'SKU00003', 'name' => 'MONZA', 'description' => 'Gafas de sol MONZA', 'price' => '49'],
            ['reference' => 'SKU00004', 'name' => 'CAMEL', 'description' => 'Gafas de sol CAMEL', 'price' => '35'],
            ['reference' => 'SKU00005', 'name' => 'FIJI', 'description' => 'Gafas de sol FIJI', 'price' => '35'],
            ['reference' => 'SKU00006', 'name' => 'THE CITY', 'description' => 'Gafas de sol THE CITY', 'price' => '59'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate($product);
        }
    }
}
