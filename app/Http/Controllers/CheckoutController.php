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
            // 'success_url' => route('home', ['success' => true]),
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel').'?session_id={CHECKOUT_SESSION_ID}',
            // 'cancel_url' => route('home', ['success' => false]),
           "metadata" =>[
            "cart_id" => $cart->id
           ]
        ];
        $customerOptions = [
            "metadata" =>[
                "mycod" => 123456789,
            ]
        ];
        // dd(Auth::user()->checkout($price, $sessionOptions));
        return Auth::user()->checkout($price, $sessionOptions, $customerOptions);
       
    }

    public function success(Request $request){
        $session_id = $request->get('session_id');
        $session= $request->user()->stripe()->checkout->sessions->retrieve($session_id);
        dd($session);
    }

    public function cancel(Request $request){
        $session_id = $request->get('session_id');
        $session= $request->user()->stripe()->checkout->sessions->retrieve($session_id);
        dd($session);
    }
}
