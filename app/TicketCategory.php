<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    public $timestamps = false;

    protected $table = 'ticket_category';

    public function ticket() {
        return $this->hasMany('App\Ticket', 'id');
    }
}
