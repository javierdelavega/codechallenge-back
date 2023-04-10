<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can login with valid credentials
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->customEmail()->create();
        $user->save();
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test user can not login with empty email
     */
    public function test_user_can_not_login_with_empty_email(): void
    {
        $user = User::factory()->customEmail()->create();
        $user->save();
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/login', [
            'email' => null,
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not login with malformed email
     */
    public function test_user_can_not_login_with_malformed_email(): void
    {
        $user = User::factory()->malformedEmail()->create();
        $user->save();
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not login with empty password
     */
    public function test_user_can_not_login_with_empty_password(): void
    {
        $user = User::factory()->customEmail()->create();
        $user->save();
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/login', [
            'email' => $user->email,
            'password' => null,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can not login if the email is not registered
     */
    public function test_user_can_not_login_if_email_not_registered(): void
    {
        $invalidEmail = "abcde@domain.com";
        $user = User::factory()->customEmail()->create();
        $user->save();
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/login', [
            'email' => $invalidEmail,
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test user can not login with wrong password
     */
    public function test_user_can_not_login_with_wrong_password(): void
    {
        $invalidPassword = "abcde";
        $user = User::factory()->customEmail()->create();
        $user->save();
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/user/login', [
            'email' => $user->email,
            'password' => $invalidPassword,
        ]);

        $response->assertStatus(403);
    }

}
