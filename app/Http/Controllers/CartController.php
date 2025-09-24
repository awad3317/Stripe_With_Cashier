<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function index(){
        return view('cart.index');
    }

    function addToCart(Course $course){
        $cart = Cart::firstOrCreate([
            'session_id' => session()->getId(),
        ]);
        $cart->courses()->syncWithoutDetaching($course);
        return back();
    }

    function removeFromCart(Course $course){
        $cart = Cart::where('session_id',session()->getId())->first();
        abort_unless($cart,404);
        $cart->courses()->detach($course);
        return back();
    }
}
