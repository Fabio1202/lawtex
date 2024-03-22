<?php

namespace App\Parsers\Base;

use Illuminate\Support\Facades\Facade;

class LawParserFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LawParser::class;
    }
}
