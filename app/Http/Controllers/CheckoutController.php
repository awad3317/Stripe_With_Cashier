<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(){
        $cart = Cart::where('session_id', request()->session()->getId())->first();
        $price = $cart->courses->pluck('stripe_price_id')->toArray();
        $sessionOptions = [
            'success_url' => route('home', ['success' => true]),
            'cancel_url' => route('home', ['success' => false]),
            "phone_number_collection"=> [
                "enabled"=> true
            ],
        ];
        // dd(Auth::user()->checkout($price, $sessionOptions));
        return Auth::user()->checkout($price, $sessionOptions);
       
    }
}
