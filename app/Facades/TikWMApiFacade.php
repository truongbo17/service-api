<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TikWMApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tikwm';
    }
}
