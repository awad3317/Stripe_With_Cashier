<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetupIntentController extends Controller
{
    public function index()  {
        
        $setupIntent=Auth::user()->createSetupIntent();
        // dd($setupIntent);
        return view('checkout.setup-intent',get_defined_vars());
    }
    
    public function post(Request $request)  {
         
            $cart=Cart::where('session_id', request()->session()->getId())->first();
            $amount= $cart->courses->sum('price');
            $paymentMethod=$request->payment_method_id;
            Auth::user()->createOrGetStripeCustomer();
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
