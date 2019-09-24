<?php

namespace Viaativa\Viaroot\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public $timestamps = false;
    //

    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }
}
