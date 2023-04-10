<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
            'id' => 1,
            'name' => 'Codechallenge',
            'email' => 'javier@smartidea.es',
            'password' => Hash::make('codechallenge'),
            'address' => 'C/ Las Chanas 100, 24410 Camponaraya (León)',
        ])->tokens()->create([
            'id' => 10005,
            'name' => 'auth_token',
            'token' => hash('sha256', 'N7fp6GTjO9CJD1QIhqv0Ty1ZZbJeS3tFIbToFJZQ'),
            'abilities' => ['api-access'],
        ]); 	

    }
}
