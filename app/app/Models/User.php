<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Prunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the cart wich owns the user.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the orders owned by the user
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the user is registered or a guest
     * 
     * @return bool true if the user is registered, false for the guest users
     */
    public function registered(): bool
    {
        return $this->email != null;
    }

    /**
     * Prune non registered users created seven days ago
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDay(2))->whereNull('email');;
    }

    /**
     * Delete the orders and cart owned by the user before prune
     */
    protected function pruning(): void
    {
        $this->cart->products()->detach();
        $this->cart->delete();
        
        foreach($this->orders as $order) {
            $order->products()->
            detach();
        }
        $this->orders()->delete();
    }
}
