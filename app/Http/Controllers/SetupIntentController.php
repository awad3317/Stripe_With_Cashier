<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupIntentController extends Controller
{
    public function index()  {
        $amount=Cart::where('session_id', request()->session()->getId())->first()->courses->sum('price');
        $payment=Auth::user()->pay($amount);
        return view('checkout.payment-intent',compact('payment'));
    }
    
    public function post(Request $request)  {
         
            $cart=Cart::where('session_id', request()->session()->getId())->first(); 
            $paymentIntentId=$request->payment_intent_id;
            $paymentIntent=Auth::user()->findPayment($paymentIntentId);
            if($paymentIntent->status == "succeeded"){
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
