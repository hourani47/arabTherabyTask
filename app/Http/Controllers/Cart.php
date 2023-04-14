<?php

namespace App\Http\Controllers;

use App\Models\Product;

class Cart
{
    /**
     * Adds a product to the cart.
     *
     * @param Product $product The product to add.
     * @param int $quantity The quantity of the product to add.
     */
    public function add(Product $product, $quantity = 1)
    {
        $cartItems = session()->get('cart.items', []);

        if (isset($cartItems[$product->id])) {
            $cartItems[$product->id]['quantity'] += $quantity;
        } else {
            $cartItems[$product->id] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart.items', $cartItems);
    }

    /**
     * Removes a product from the cart.
     *
     * @param int $productId The ID of the product to remove.
     */
    public function remove($productId)
    {
        $cartItems = session()->get('cart.items', []);
        unset($cartItems[$productId]);

        session()->put('cart.items', $cartItems);
    }

    /**
     * Retrieves all items in the cart.
     *
     * @return \Illuminate\Support\Collection The items in the cart.
     */
    public function getItems()
    {
        $data = collect(session()->get('cart.items', []));
        $data->forget('items');
        return $data;
    }

    /**
     * Clears all items from the cart.
     */
    public function clear()
    {
        session()->forget('cart.items');
    }

    /**
     * Calculates the total price of all items in the cart.
     *
     * @return float The total price of all items in the cart.
     */
    public function getTotal()
    {
        $data = $this->getItems();
        return $this->sum($data);
    }

    /**
     * Calculates the sum of all items in a given collection.
     *
     * @param \Illuminate\Support\Collection $items The items to sum.
     * @return float The sum of all items in the collection.
     */
    public function sum($items)
    {
        $total = 0;

        foreach ($items as $item) {
            $total += $item['product']->price * $item['quantity'];
        }

        return $total;
    }

    /**
     * Updates the quantity of a product in the cart.
     *
     * @param Product $product The product to update.
     * @param int $quantity The new quantity of the product.
     */
    public function update(Product $product, $quantity)
    {
        $cartItems = session()->get('cart.items', []);
        $cartItems[$product->id] = [
            'product' => $product,
            'quantity' => $quantity,
        ];
        session()->put('cart.items', $cartItems);
    }
}
