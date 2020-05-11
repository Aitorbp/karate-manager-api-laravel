<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BidBetweenRivals extends Model
{
    protected $table = 'bid_between_rivals';
    protected $fillable = [
        'bid_rival', 'id_karateka','id_participant_bid_send','id_participant_bid_receive'
    ];

}
