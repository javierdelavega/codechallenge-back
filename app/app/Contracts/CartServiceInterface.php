<?php

namespace App\Contracts;

interface CartServiceInterface 
{
    /**
     * Return the content of the cart
     *
     * @return Illuminate\Support\Collection The content of the cart
     */
    public function content();

    /**
     * Add a new product to the cart
     *
     * @param int $id id of the product
     * @param int $quantity quantity
     * @return void
     */
    public function add($id, $quantity);

    /**
     * Update the quantity of a product in the cart
     *
     * @param int $id id of the product
     * @param int $quantity quantity
     * @return void
     */
    public function update($id, $quantity);

    /**
     * Remove a product from the cart
     *
     * @param int $id id of the product
     * @return int number of products removed
     */
    public function remove($id);

    /**
     * Return the number of products in the cart
     *
     * @return int the number of products in the cart
     */
    public function count();

    /**
     * Return the total price of the products in the cart
     *
     * @return float the total price of the products in the cart
     */
    public function total();

    /**
     * Empty the cart
     *
     * @return void
     */
    public function empty();

    /**
     * Confirm the cart. Creates a order with the content of the cart
     * storing the current price of the products
     *
     * @return bool true if success false if fails
     */
    public function confirm();
}