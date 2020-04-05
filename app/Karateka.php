<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karateka extends Model
{
    protected $fillable = [
        'name', 'country','gender','weight', 'photo_karateka'
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

    public function karatekaResult()
    {
        return $this->belongsToMany('App\ResultKarateka', 'id_karateka');
    }

    public function karatekasByGroupInMarket()
    {
        return $this->belongsToMany('App\Karateka', 'market', 'id_group', 'id_karatekas');
    }
}
