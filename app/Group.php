<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function participants()
    {
        return $this->hasMany('App\Participant');
    }

    public function groupByParticipant()
    {
        return $this->belongsToMany('App\Group', 'participants', 'id_user','id_group');
    }
}
