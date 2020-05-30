<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultKarateka extends Model
{
    protected $table = 'result_karatekas';
    protected $fillable = [
        'points', 'points_total', 'id_championship', 'id_karateka'
    ];
}
