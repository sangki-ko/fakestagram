<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class NewTokenFacade extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'NewToken';
    }
}