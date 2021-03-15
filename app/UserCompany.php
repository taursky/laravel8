<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    public $timestamps = false;

    protected $table = 'user_company';

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'idu');
    }
}
