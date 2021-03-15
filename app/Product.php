<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'name', 'brand', 'articul', 'balance', 'prise', 'provider'
    ];

    public $timestamps = false;

    //protected $table = 'products';
}
