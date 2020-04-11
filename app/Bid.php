<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $table = 'bids';
    protected $fillable = [
        'id_market', 'id_group','id_karatekas','id_participants', 'bid'
    ];


}
