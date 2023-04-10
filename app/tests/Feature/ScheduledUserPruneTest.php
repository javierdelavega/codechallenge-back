<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScheduledUserPruneTest extends TestCase
{
    // Undo the changes in the database after running each test
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->artisan('schedule:run')->assertExitCode(0);
    }

    /**
     * A basic feature test example.
     */
    public function test_example2(): void
    {
        $this->artisan('model:prune')->assertExitCode(0);
    }

}
