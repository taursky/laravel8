<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailOrder extends Model
{
    public $timestamps = false;

    protected $table = 'orders';
}
