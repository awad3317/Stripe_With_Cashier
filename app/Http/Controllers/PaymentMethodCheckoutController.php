<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMethodCheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.payment-method');
    }
}
