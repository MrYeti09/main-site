<?php

namespace Viaativa\Viaroot\Facades;

use Illuminate\Support\Facades\Facade;

class Viaativa extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'viaativa';
    }
}
