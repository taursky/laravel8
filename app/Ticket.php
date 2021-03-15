<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = false;

    protected $table = 'tickets';

    public function ticket_mes() {
        return $this->hasMany('App\TicketMes', 'id');
    }

    public function categories(){
        return $this->belongsTo('App\TicketCategory', 'category');
    }
}
