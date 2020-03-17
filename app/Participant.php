<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public function group()
    {
        return $this->belongsTo('App\Group','id_group');
    }
}
