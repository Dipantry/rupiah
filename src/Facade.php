<?php

namespace Dipantry\Rupiah;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    protected static function getFacadeAccessor(): string
    {
        return 'rupiah';
    }
}
