<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class UserGetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if can get the data of the current guest user
     */
    public function test_can_get_current_guest_user_data(): void
    {
        $user = User::factory()->guest()->create();
        Sanctum::actingAs($user);

        $response = $this->get('/api/user');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'name' => null,
                'email' => null,
                'address' => null,
                'registered' => false,
            ]);
    }

    /**
     * Test if can get the data of the current registered user
     */
    public function test_can_get_current_registered_user_data(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->get('/api/user');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'registered' => true,
            ]);
    }

}
