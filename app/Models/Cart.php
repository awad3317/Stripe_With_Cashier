<?php

namespace App\Models;

use Laravel\Cashier\Cashier;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    protected $with = ['courses'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'cart_course');
    }

    public function total(){
        return Cashier::formatAmount($this->courses->sum('price'));
    }
}
