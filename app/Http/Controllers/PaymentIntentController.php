<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentIntentController extends Controller
{
    public function index()  {
        $amount=Cart::where('session_id', request()->session()->getId())->first()->courses->sum('price');
        $payment=Auth::user()->pay($amount);
        return view('checkout.payment-intent',compact('payment'));
    }
}
