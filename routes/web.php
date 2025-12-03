<?php

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentMethodCheckoutController;
use App\Http\Controllers\ProfileController;
use Laravel\Cashier\Checkout;

Route::get('/', function () {
    $courses = Course::all();
    return view('home',get_defined_vars());
})->name('home');

Route::controller(CourseController::class)->group(function () {
    Route::get('/courses/{course:slug}', 'show')->name('courses.show');
});

// Cart Management

Route::controller(CartController::class)->group(function(){
    Route::get('/cart', 'index')->name('cart.index');
    Route::get('/addToCart/{course:slug}', 'addToCart')->name('addToCart');
    Route::get('/removeFromCart/{course:slug}', 'removeFromCart')->name('removeFromCart');
});

// Checkout Management

Route::controller(CheckoutController::class)->group(function(){
    Route::get('/checkout', 'checkout')->name('checkout')->middleware('auth');
    Route::get('/checkout/enableCoupons', 'enableCoupons')->name('checkout.enableCoupons')->middleware('auth');
    Route::get('/checkout/nonStripeProduct', 'nonStripeProduct')->name('checkout.nonStripeProduct')->middleware('auth');
    Route::get('/checkout/lineItems', 'lineItems')->name('checkout.lineItems')->middleware('auth');
    Route::get('/checkout/guest', 'guest')->name('checkout.guest');
    Route::get('/checkout/success', 'success')->name('checkout.success')->middleware('auth');
    Route::get('/checkout/cancel', 'cancel')->name('checkout.cancel')->middleware('auth');
});

// Direct Integration - Payment Method 
Route::controller(PaymentMethodCheckoutController::class)->group(function(){
    Route::get('/direct/paymentMethod', 'index')->name('direct.paymentMethod')->middleware('auth');
    Route::post('/direct/paymentMethod/post', 'post')->name('direct.paymentMethod.post')->middleware('auth');
    
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
