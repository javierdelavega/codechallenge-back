<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserGetTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if can issue new access token for unauthenticated requests
     */
    public function test_can_create_new_access_token(): void
    {
        $response = $this->get('/api/token');

        $response
            ->assertStatus(200)
            ->assertJson([
                'token' => true,
            ]);
    }
}
