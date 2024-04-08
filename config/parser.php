<?php

return [
    'parser_classes' => [
        'gesetze-im-internet.de' => App\Parsers\GesetzeImInternetParser::class,
        'dsgvo-gesetz.de' => App\Parsers\DSGVOGesetz::class,
    ],
];
