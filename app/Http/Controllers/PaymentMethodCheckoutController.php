<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMethodCheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.payment-method');
    }

    public function post(Request $request)
    {
        $paymentMethodId = $request->input('payment_method');

        // Here you can attach the payment method to the user or process it as needed
        // For example:
        $user = $request->user();
        $user->addPaymentMethod($paymentMethodId);

        return redirect()->route('dashboard')->with('success', 'Payment Method added successfully!');
    }
}
