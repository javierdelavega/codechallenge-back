<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can register with valid data
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $user = User::factory()->customEmail()->make();
        Sanctum::actingAs(User::factory()->guest()->make());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'address' => $user->address,
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test user can not register with empty email
     */
    public function test_user_can_not_register_with_empty_email(): void
    {
        $user = User::factory()->customEmail()->make();
        Sanctum::actingAs(User::factory()->guest()->make());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/register', [
            'name' => $user->name,
            'email' => null,
            'password' => 'password',
            'address' => $user->address,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not register with malformed email
     */
    public function test_user_can_not_register_with_malformed_email(): void
    {
        $user = User::factory()->malformedEmail()->make();
        Sanctum::actingAs(User::factory()->guest()->make());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'address' => $user->address,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not register with empty password
     */
    public function test_user_can_not_register_with_empty_password(): void
    {
        $user = User::factory()->customEmail()->make();
        Sanctum::actingAs(User::factory()->guest()->make());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => null,
            'address' => $user->address,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not register with empty name
     */
    public function test_user_can_not_register_with_empty_name(): void
    {
        $user = User::factory()->customEmail()->make();
        Sanctum::actingAs(User::factory()->guest()->make());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/register', [
            'name' => null,
            'email' => $user->email,
            'password' => 'password',
            'address' => $user->address,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not register with empty address
     */
    public function test_user_can_not_register_with_empty_address(): void
    {
        $user = User::factory()->customEmail()->make();
        Sanctum::actingAs(User::factory()->guest()->make());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'address' => null,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not register if the email is already registered
     */
    public function test_user_can_not_login_if_email_not_registered(): void
    {
        $user = User::factory()->customEmail()->create();
        Sanctum::actingAs(User::factory()->guest()->make());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'address' => $user->address,
        ]);

        $response->assertStatus(422);
    }

}
