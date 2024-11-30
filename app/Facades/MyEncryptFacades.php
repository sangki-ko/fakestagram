<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MyEncryptFacades extends Facade {
    protected static function getFacadeAccessor() {
        return 'MyEncrypt';
    }
}