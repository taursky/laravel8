<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model
{
    public $timestamps = false;

    protected $table = 'balance_history';
}
