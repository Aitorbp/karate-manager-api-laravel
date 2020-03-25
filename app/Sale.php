<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'value', 'id_group', 'id_participants', 'id_karatekas'
    ];
}
