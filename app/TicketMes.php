<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketMes extends Model
{
    public $timestamps = false;

    protected $table = 'tickets_mes';

    public function tickets() {
        return $this->belongsTo('App\Ticket', 'id_ticket');
    }
}
