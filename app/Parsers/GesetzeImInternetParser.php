<?php

namespace App\Parsers;

use App\Models\Law;
use App\Parsers\Base\ParsedInformation;
use App\Parsers\Base\ParsedLaw;
use App\Parsers\Base\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class GesetzeImInternetParser implements ParserInterface
{
    public function fullParse(Law $law, Crawler $crawler): ParsedLaw
    {
        $parsedLaw = new ParsedLaw();
        $parsedLaw->lawBookSlug = $law->lawBook->slug;
        $parsedLaw->lawBookTitle = $law->lawBook->name;

        $parsedLaw->title = $law->name;
        $parsedLaw->section = $law->slug;

        $parsedLaw->url = $law->url;

        $parsedLaw->paragraphs = [];

        $crawler->filter('div.jurAbsatz')->each(function (Crawler $node) use ($parsedLaw) {
            $parsedLaw->paragraphs[] = preg_replace('/^\([0-9]*\) /', '', $node->text());
        });

        return $parsedLaw;
    }

    public function parseInformation(Crawler $crawler): ParsedInformation
    {
        $parsedInformation = new ParsedInformation();

        $prefixSlug = explode(' ', $crawler->filter('span.jnenbez')->text());
        $parsedInformation->lawPrefix = $prefixSlug[0];
        $parsedInformation->lawSlug = $prefixSlug[1];
        $parsedInformation->lawUrl = $crawler->getUri();

        $parsedInformation->lawTitle = $crawler->filter('span.jnentitel')->text();
        $matches = [];
        preg_match("/\([0-9a-zA-Z]*\)/", $crawler->filter('div.jnheader h1')->text(), $matches);
        $parsedInformation->lawBookSlug = str_replace(['(', ')'], '', $matches[0]);
        $parsedInformation->lawBookTitle = preg_replace('/ \([0-9a-zA-Z]*\).*/', '', $crawler->filter('div.jnheader h1')->text());

        return $parsedInformation;
    }
}
