<?php

namespace App\Models;

use Laravel\Cashier\Cashier;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_course');
    }

    public function price()
    {
        return Cashier::formatAmount($this->price);
    }
}
