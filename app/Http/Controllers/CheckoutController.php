<?php

namespace App\Http\Controllers;

use App\Mail\SuccessfullPayment;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\RateLimitException;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page with the total amount.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $amount = (new Cart)->getTotal();

        return view('checkout', compact('amount'));
    }

    /**
     * Process the payment and complete the checkout process.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Create a new Stripe client using the secret key
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Create a Stripe token for the card
            $res = $stripe->tokens->create([
                'card' => [
                    'number' => $request->input('card_nunmber'),
                    'exp_month' => $request->input('card_expiry_month'),
                    'exp_year' => $request->input('card_expiry_year'),
                    'cvc' => $request->input('card_cvc'),
                ],
            ]);

            // Charge the customer using the token
            $stripe->charges->create([
                'amount' => $request->input('amount'),
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $res->description,
            ]);

            // Complete the checkout process
            $this->checkout($request);

            // Show the success page
            return view('checkout_success');
        } catch (CardException $e) {
            // Handle declined card error
            return redirect()->back()->with('error', $e->getMessage());
        } catch (RateLimitException $e) {
            // Handle rate limit error
            return redirect()->back()->with('error', $e->getMessage());
        } catch (InvalidRequestException $e) {
            // Handle invalid request error
            return redirect()->back()->with('error', $e->getMessage());
        } catch (AuthenticationException $e) {
            // Handle authentication error
            return redirect()->back()->with('error', $e->getMessage());
        } catch (ApiConnectionException $e) {
            // Handle API connection error
            return redirect()->back()->with('error', $e->getMessage());
        } catch (ApiErrorException $e) {
            // Handle generic API error
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            // Handle other exceptions
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the success page.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('checkout_success');
    }

    /**
     * Complete the checkout process by creating an order, adding cart items, and sending an email.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function checkout(Request $request)
    {
        // Get the user who is placing the order
        $user = auth()->user();

        // Get the items in the cart
        $cartItems = session()->get('cart.items', []);

        // Calculate the total price of the items in the cart
        $totalPrice = collect($cartItems)->sum(function ($item) {
            return $item['quantity'] * $item['product']->price;
        });

        // Create a new order
        $order = new Order();
        $order->user_id = $user->id;
        $order->total_price = $totalPrice;
        $order->save();

        // Add the cart items to the order as line items
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem['product']->id);
            $order->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $cartItem['quantity'],
                'price' => $cartItem['product']->price,
                'total_price' => $cartItem['quantity'] * $cartItem['product']->price,
            ]);
        }

        // Clear the cart
        session()->forget('cart');

        // Send email to the user
        Mail::to($user->email)->send(new SuccessfullPayment($request->input('amount')));

    }

}
