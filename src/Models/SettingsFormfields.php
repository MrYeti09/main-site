<?php

namespace Viaativa\Viaroot\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsFormfields extends Model
{
    public $timestamps = false;
    //

    public function getCodename()
    {
        return $this->slug;
    }

    public function getName()
    {
        return $this->name;
    }
}
