<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    //

    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => ['name' => 'Test order'],
                        'unit_amount'  => $request->amount * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success') . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('stripe.cancel'),
        ]);

        return response()->json(['url' => $session->url]);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        // Optionally verify the session with Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        dd($session);

        // $session->payment_status === 'paid'

        return view('stripe.success', compact('session'));
        // or return redirect('/')->with('success', 'Payment successful!');
    }

    public function cancel()
    {
        return view('stripe.cancel');
        // or return redirect('/')->with('error', 'Payment cancelled.');
    }
}
