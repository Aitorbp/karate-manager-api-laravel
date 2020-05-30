<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultParticipant extends Model
{
    protected $table = 'result_participants';
    protected $fillable = [
        'points', 'points_total', 'id_championship', 'id_karateka'
    ];
}
