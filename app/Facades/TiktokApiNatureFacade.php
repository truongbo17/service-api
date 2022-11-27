<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TiktokApiNatureFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tiktok-api-nature';
    }
}
