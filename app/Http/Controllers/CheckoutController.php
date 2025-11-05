<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\order;
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

    public function enableCoupons(){
        $cart = Cart::where('session_id', request()->session()->getId())->first();
        $price = $cart->courses->pluck('stripe_price_id')->toArray();
        $sessionOptions = [
            'success_url' => route('home', ['message' => 'Payment Successful!']),
            'cancel_url' => route('home', ['message' => 'Payment Cancelled!']),
            // "allow_promotion_codes"=> true,
        ];
        
        return Auth::user()
        // ->allowPromotionCodes()
        // ->withPromotionCode('promo_1SFCtTGnl6rxKSPsuwENeuxI')
        // ->withCoupon('eYDCmBbU')
        ->checkout($price, $sessionOptions);
       
    }

    public function nonStripeProducts(){
        $cart = Cart::where('session_id', request()->session()->getId())->first();
        $amount = $cart->courses->sum('price');

        $sessionOptions = [
            'success_url' => route('home', ['message' => 'Payment Successful!']),
            'cancel_url' => route('home', ['message' => 'Payment Cancelled!']),
            
        ];
        
        return Auth::user()->checkoutCharge($amount, 'courses bundles', 1, $sessionOptions);
       
    }

    public function lineItems(){
        $cart = Cart::where('session_id', request()->session()->getId())->first();
        $courses = $cart->courses->map(function($course){
            return [
                'price_data' => [
                    'currency' => env('CASHIER_CURRENCY','usd'),
                    'product_data' => [
                        'name' => $course->name
                    ],
                    'unit_amount' => $course->price,
                    // 'tax_behavior' => 'exclusive',
                ],
                // 'adjustable_quantity' => [
                //     'enabled' => true,
                //     'minimum' => 1,
                //     'maximum' => 10,
                // ],
                'quantity' => 1,
            ];
        })->toArray();

        $sessionOptions = [
            'success_url' => route('home', ['message' => 'Payment Successful!']),
            'cancel_url' => route('home', ['message' => 'Payment Cancelled!']),
            'line_items' => $courses
            
        ];
        
        return Auth::user()->checkout(null, $sessionOptions);
       
    }

    public function success(Request $request){
        $session= $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));

        if($session->payment_status == 'paid'){
            $cart_id = Cart::findOrFail($session->metadata->cart_id);

            $order = order::create([
                'user_id' => $request->user()->id,
            ]);

            $order->courses()->attach($cart_id->courses->pluck('id')->toArray());

            $cart_id->delete();

            return redirect()->route('home',['success'=>'Payment Successful!']);
        }
        
    }

    public function cancel(Request $request){
        $session= $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));
    }
}
