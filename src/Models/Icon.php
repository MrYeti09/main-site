<?php

namespace Viaativa\Viaroot\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    function getIconsAttribute($icons){
        return json_decode($icons);
    }

    function setIconsAttribute($icons){
        $this->attributes['icons'] = json_encode($icons);
    }
}
