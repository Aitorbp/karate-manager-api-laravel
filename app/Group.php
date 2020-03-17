<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function participants()
    {
        return $this->hasMany('App\Participant');
    }
}
