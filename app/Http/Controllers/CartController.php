<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * The Cart instance.
     *
     * @var Cart
     */
    protected $cart;

    /**
     * Create a new CartController instance.
     *
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Show the products page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::all();

        return view('products', compact('products'));
    }

    /**
     * Remove an item from the cart.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeItemFromCart(Product $product)
    {
        $this->cart->remove($product->id);

        return redirect()->route('cart.show')->with('success', "{$product->name} removed from cart.");
    }

    /**
     * Clear the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCart()
    {
        $this->cart->clear();

        return redirect()->route('products.cart')->with('success', "Cart cleared.");
    }

    /**
     * Show the cart.
     *
     * @return \Illuminate\View\View
     */
    public function showCart()
    {
        $items = $this->cart->getItems();
        $total = $this->cart->getTotal();

        return view('cart', compact('items', 'total'));
    }

    /**
     * Add a product to the cart.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        $this->cart->add($product, $quantity);

        return redirect()->route('home')->with('success', "{$product->name} added to cart.");

    }

    /**
     * Update a product quantity in the cart.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        $this->cart->update($product, $quantity);

        return redirect()->back();
    }
}
