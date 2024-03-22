<?php

namespace App\Parsers\Base;

use App\Models\Law;
use Symfony\Component\DomCrawler\Crawler;

interface ParserInterface
{
    public function fullParse(Law $law, Crawler $crawler): ParsedLaw;

    public function parseInformation(Crawler $crawler): ParsedInformation;
}
