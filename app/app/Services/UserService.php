<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use App\Models\User;

class UserService {

    protected $user;

    /**
     * Constructs a new UserService object and initializes the user.
     * If the user is not authenticated, creates a new Guest user with random name starting with 'Guest'
     */
    public function __construct()
    {
        $user = auth('sanctum')->user();

        if ($user == null) {
            $randomNumbers = rand(pow(10, 5 - 1), pow(10, 5) - 1);
            $user = User::create([
                'name' => "Guest{$randomNumbers}",
            ]);
        }
        $this->user = $user;
    }

    /**
     * Return a new access token labeled as guest_token for the guest users
     * or labeled as auth_token for the registered users.
     *
     * @return Laravel\Sanctum\NewAccessToken new access token
     */
    public function getToken(): NewAccessToken
    {
        return $this->user->registered() ? $this->user->createToken('auth_token') : $this->user->createToken('guest_token');
    }

    /**
     * Check given credentials, if valid returns a new auth_token 
     * type access token for the registered user
     *
     * @param string $email the login email
     * @param string $password the login password
     * 
     * @return bool true if success false if failed
     */
    public function login($email, $password): bool
    {
        $user = User::where('email', $email)->first();
     
        if ($user && Hash::check($password, $user->password)) {
            $this->user = $user;
            return true;
        }
        return false;

    }

    /**
     * Converts a Guest user into Regular user with email, password, name, and address
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string $password
     * 
     * @return bool true if registered ok. false if the given email is already registered
     */
    public function register(string $email, string $password, string $name, string $address): bool
    {
        $registered = false;

        if (!$this->user->registered()) {
            $this->user->email = $email;
            $this->user->password = Hash::make($password);
            $this->user->name = $name;
            $this->user->address = $address;
            $this->user->save();

            $registered = true;
        }

        return $registered;
    }

    /**
     * Returns the orders of the current user
     *
     * @return Illuminate\Database\Eloquent\Collection orders
     */
    public function getOrders(): Collection
    {
        return $this->user->orders;
    }

    /**
     * Returns the current User instance
     *
     * @return User the current user instance
     */
    public function getUser(): User
    {
        return($this->user);
    }

}