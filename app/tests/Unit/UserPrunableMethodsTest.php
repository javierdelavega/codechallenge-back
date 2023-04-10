<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPrunableMethodsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** 
     * @test
     * Test prunable method in User model
     */
    public function user_model_prunable_method_works()
    {
        $user = User::factory()->make();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $user->prunable()); 
    }

    /** 
     * @test
     * @doesNotPerformAssertions
     * Test prunable method in User model
     */
    public function user_model_pruning_method_works()
    {
        $class = new \ReflectionClass('App\Models\User');
        $myProtectedMethod = $class->getMethod('pruning');
        $myProtectedMethod->setAccessible(true);

        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);
        $myProtectedMethod->invoke($user);
        
    }

}
