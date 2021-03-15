<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $casts = [
        'data' => 'array',
    ];

    protected $table = 'orders';
}
