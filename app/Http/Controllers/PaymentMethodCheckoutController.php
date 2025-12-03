<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodCheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.payment-method');
    }

    public function post(Request $request)
    {
        $cart=Cart::where('session_id', request()->session()->getId())->first();
        $amount= $cart->courses->sum('price');
        $paymentMethod=$request->payment_method;
        $payment=Auth::user()->charge($amount,$paymentMethod,[
            'return_url'=>route('home'),        ]); 
        if($payment->status == "succeeded"){
            $order= order::create([
                'user_id'=>Auth::user()->id,
            ]);
            $courseIds = $cart->courses->pluck('id')->toArray();
$order->courses()->attach($courseIds);
$cart->delete();
            return redirect()->route('home');
        }
    }
}
