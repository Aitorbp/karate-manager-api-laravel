<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $table = 'market';
    protected $fillable = [
        'date_release', 'id_group','id_karatekas'
    ];


    public function groups()
    {
        return $this->belongsTo('App\Group','id_group');
    }

    public function karatekas()
    {
        return $this->belongsTo('App\Karateka','id_karatekas');

    }
}
