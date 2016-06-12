<?php

namespace geetest\Facades;

use Illuminate\Support\Facades\Facade;

class geetest extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'geetest';
    }
}
?>
