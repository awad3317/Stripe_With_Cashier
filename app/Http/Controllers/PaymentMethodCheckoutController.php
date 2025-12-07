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
        if($request->payment_method){
            Auth::user()->updateOrCreateStripeCustomer();
            // Auth::user()->addPaymentMethod($request->payment_method);
            Auth::user()->updateDefaultPaymentMethod($request->payment_method);
            
        }
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

    public function oneClick(Request $request) {
        if(Auth::user()->hasDefaultPaymentMethod()){
            $cart=Cart::where('session_id', request()->session()->getId())->first();
            $amount= $cart->courses->sum('price');
            $paymentMethod=Auth::user()->defaultPaymentMethod()->id;
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
}
