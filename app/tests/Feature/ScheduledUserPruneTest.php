<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;

class ScheduledUserPruneTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * Test artisan model:prune command prunes guest users created 7 days ago
     */
    public function test_artisan_command_prune_only_guest_users_seven_days_old(): void
    {
        $prunableUser = User::factory()->guest()->create();
        $prunableUser->created_at = now()->subDay(User::PRUNABLE_DAYS + 1);
        $prunableUser->name = 'prunable';
        $prunableUser->save();
        Cart::create(['user_id' => $prunableUser->id]);

        $user = User::factory()->guest()->create();
        $user->created_at = now()->subDay(User::PRUNABLE_DAYS - 1);
        $user->name = 'guest';
        $user->save();
        Cart::create(['user_id' => $user->id]);

        $regUser = User::factory()->create();
        $regUser->created_at = now()->subDay(User::PRUNABLE_DAYS + 1);
        $regUser->name = 'registered';
        $regUser->save();
        Cart::create(['user_id' => $regUser->id]);

        $this->artisan('model:prune')->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $regUser->name,
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => $prunableUser->name,
        ]);
    }

}
