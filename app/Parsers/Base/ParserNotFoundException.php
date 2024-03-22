<?php

namespace App\Parsers\Base;

class ParserNotFoundException extends \Exception
{
    public function __construct(string $host)
    {
        parent::__construct("No parser found for host: $host");
    }
}
