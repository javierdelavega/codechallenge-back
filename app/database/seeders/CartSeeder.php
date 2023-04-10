<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);

        $carts = [
        	['user_id' => $user->id],            
        ];

        foreach ($carts as $cart) {
            Cart::updateOrCreate($cart);
        }
    }
}
