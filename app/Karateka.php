<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karateka extends Model
{
    protected $fillable = [
        'name', 'App', 'country','gender','weight', 'injured', 'discontinued', 'value'
    ];

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function participants()
    {
        return $this->hasMany('App\Participant');
    }

    public function championship()
    {
        return $this->hasMany('App\Championship');
    }
}
