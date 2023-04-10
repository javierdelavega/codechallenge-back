<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Contracts\CartServiceInterface;
use App\Services\UserService;
use App\Models\Cart;
use App\Models\Order;

class CartService implements CartServiceInterface {

    protected $cart;

    /**
     * Constructs a new CartService object
     */
    public function __construct(UserService $userService)
    {
        $this->cart = $this->getCart($userService->getUser());
    }

    /**
     * Add a new product to the cart
     *
     * @param int $id id of the product
     * @param int $quantity quantity
     * @return void
     */
    public function add($id, $quantity): void
    {
        // If the product exists in the cart, update it
        if ($this->cart->products()->where('product_id', $id)->exists()) {

            // Update quantity: received quantity + current quantity
            $cartProduct = $this->cart->products()->find($id);
            $quantity += $cartProduct->pivot->quantity;
            
            $this->update($id, $quantity);
        // If the product not exists in the cart, add it
        } else {
            $this->cart->products()->attach($id, ['quantity' => $quantity]);
        }
    }

    /**
     * Update the quantity of a product in the cart
     *
     * @param int $id id of the product
     * @param int $quantity quantity
     * @return void
     */
    public function update($id, $quantity): void
    {
        // Check if the product exists in the cart
        $cartProduct = $this->cart->products()->findOrFail($id);

        // Update the quantity attribute oof the product in the cart
        $this->cart->products()->syncWithoutDetaching([ $cartProduct->id => ['quantity' => $quantity]]);
    }

    /**
     * Remove a product from the cart
     *
     * @param int $id id of the product
     * @return int number of products removed
     */
    public function remove($id): int
    {
        return $this->cart->products()->detach($id);
    }

    /**
     * Empty the cart
     *
     * @return void
     */
    public function empty(): void
    {
        $this->cart->products()->detach();
    }

    /**
     * Return the number of products in the cart
     *
     * @return int the number of products in the cart
     */
    public function count(): int
    {
        $count = 0;
        foreach ($this->cart->products as $product) {
            $count += $product->pivot->quantity;
        }
        return $count;
    }

    /**
     * Return the content of the cart
     *
     * @return Illuminate\Support\Collection The content of the cart
     */
    public function content(): Collection
    {
        return $this->cart->products;
    }

    /**
     * Return the total price of the products in the cart
     *
     * @return float the total price of the products in the cart
     */
    public function total(): float
    {
        $total = 0;
        foreach ($this->cart->products as $product) {
            $total += $product->price * $product->pivot->quantity;
        }

        return $total;
    }

    /**
     * Confirm the cart. Creates a order with the content of the cart
     * storing the current price of the products
     *
     * @return bool true if success false if error
     */
    public function confirm(): bool
    {
        $checkout = false;
        // only registered users can confirm the cart
        if ($this->cart->user->registered()) {
            // check the cart is not empty
            if ($this->cart->products->count() > 0) {
                $order = Order::create([
                    'user_id' => $this->cart->user->id,
                    'address' => $this->cart->user->address,
                    'total' => $this->total(),
                ]);
    
                foreach($this->cart->products as $product) {
                    $order->products()->attach($product->id, [
                        'quantity' => $product->pivot->quantity, 
                        'price' => $product->price,
                    ]);
                }
    
                $checkout = true;
                // empty the cart after the order is stored
                $this->empty();
            }
            
        }
        
        return $checkout;
    }

    /**
     * If the user already has a cart return that cart
     * If the user doesn't has a cart create a new cart and asocciate it with the user
     * 
     * @return \App\Moodels\Cart cart object associated to the user
     */
    protected function getCart($user): Cart
    {
        if (!$user->cart()->exists()) {
            $user->cart = Cart::create(['user_id' => $user->id]);
        } 
        return $user->cart;
    }
}