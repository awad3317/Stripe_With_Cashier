<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function index(){
        $cart = Cart::where('session_id',session()->getId())->first();
        return view('cart.index',get_defined_vars());
    }

    function addToCart(Course $course){
        $cart = Cart::firstOrCreate([
            'session_id' => session()->getId(),
        ]);
        $cart->courses()->syncWithoutDetaching($course);
        return back();
    }
}
