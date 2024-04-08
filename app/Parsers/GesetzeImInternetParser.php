<?php

namespace App\Parsers;

use App\Models\Law;
use App\Parsers\Base\FullLawParseFromLaw;
use App\Parsers\Base\ParsedInformation;
use App\Parsers\Base\ParsedLaw;
use App\Parsers\Base\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class GesetzeImInternetParser implements ParserInterface
{
    use FullLawParseFromLaw;

    public function fullParse(Law $law, Crawler $crawler): ParsedLaw
    {
        $paragraphs = [];

        $crawler->filter('div.jurAbsatz')->each(function (Crawler $node) use (&$paragraphs) {
            $txt = $node->innerText();
            $filteredDl = $node->filter('dl');
            if ($filteredDl->count() > 0) {
                $filteredDl->filter('dt')->each(function (Crawler $dl) use (&$txt) {
                    $txt .= '<br>'.$dl->text();
                    $txt .= ' '.$dl->nextAll()->text();
                });
            }
            $paragraphs[] = preg_replace('/^\([0-9]*\) /', '', $txt);
        });

        return $this->autoFullParse($law, $paragraphs);
    }

    public function parseInformation(Crawler $crawler): ParsedInformation
    {
        $prefixSlug = explode(' ', $crawler->filter('span.jnenbez')->text());
        $lawPrefix = $prefixSlug[0];
        $lawSlug = $prefixSlug[1];
        $lawUrl = $crawler->getUri();

        $lawTitle = $crawler->filter('span.jnentitel')->text();
        $matches = [];
        preg_match("/\([0-9a-zA-Z]*\)/", $crawler->filter('div.jnheader h1')->innerText(), $matches);
        if ($matches === []) {
            // Get first query parameter
            $lawBookSlug = explode('/', $crawler->getUri())[3];
            $lawBookTitle = $crawler->filter('div.jnheader h1')->innerText();
        } else {
            $lawBookSlug = str_replace(['(', ')'], '', $matches[0]);
            $lawBookTitle = preg_replace('/ \([0-9a-zA-Z]*\).*/', '', $crawler->filter('div.jnheader h1')->text());
        }

        return new ParsedInformation($lawBookTitle, $lawBookSlug, $lawSlug, $lawPrefix, $lawTitle, $lawUrl);
    }
}
