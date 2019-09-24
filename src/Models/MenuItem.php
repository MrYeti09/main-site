<?php

namespace Viaativa\Viaroot\Models;

use TCG\Voyager\Models\MenuItem as VoyagerMenuItem;



class MenuItem extends VoyagerMenuItem
{
   function getPermissionsAttribute($value){
       return json_decode($value);
   }


   function setPermissionsAttribute($value){
       return json_encode($value);
   }
}
