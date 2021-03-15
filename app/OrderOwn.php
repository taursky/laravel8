<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderOwn extends Model
{
    public $timestamps = false;

    protected $table = 'orders';
}
