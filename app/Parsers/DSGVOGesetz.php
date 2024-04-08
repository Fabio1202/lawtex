<?php

namespace App\Parsers;

use App\Models\Law;
use App\Parsers\Base\FullLawParseFromLaw;
use App\Parsers\Base\ParsedInformation;
use App\Parsers\Base\ParsedLaw;
use App\Parsers\Base\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class DSGVOGesetz implements ParserInterface
{

    use FullLawParseFromLaw;

    public function fullParse(Law $law, Crawler $crawler): ParsedLaw
    {
        $paragraphs = [];

        $items = $crawler->filter("div.entry-content > ol > li");

        if($items->count() === 0) {
            $items = $crawler->filter("div.entry-content > p");
        }

        $items->each(function (Crawler $node) use (&$paragraphs) {
            // Get node html, but without inner <ol> tags
            $paragraph = $node->html();
            // Replace newlines with <br> tags
            $paragraph = str_replace("\n", '', $paragraph);
            // Replace <ol> * </ol> with nothing
            $paragraph = preg_replace('/<ol>.*<\/ol>/', '', $paragraph);

            //dump($paragraph);

            if($node->filter('ol')->count() > 0) {
                $node->filter('ol > li')->each(function (Crawler $subNode, int $index) use (&$paragraph) {
                    $paragraph .= '<br>' . $index+1 . '. ' . $subNode->html();
                });
            }

            // replace <sub> * </sub> with nothing
            $paragraph = preg_replace('/<sup>.*?<\/sup>/', '', $paragraph);
            // HTML to text, except for <br> tags
            $paragraph = strip_tags($paragraph, '<br>');
            $paragraphs[] = $paragraph;
        });

        return $this->autoFullParse($law, $paragraphs);
    }

    public function parseInformation(Crawler $crawler): ParsedInformation
    {
        // First url parameter is the law book slug
        $lawBookSlug = explode('/', $crawler->getUri())[3];
        if($lawBookSlug === 'erwaegungsgruende') {
            $lawBookSlug = 'DSGVO-ErwG';
            $lawBookTitle = 'Erwägungsgründe zur Datenschutz-Grundverordnung';
            // Second url parameter and 'nr-' replaced by empty string is the law slug
            $lawSlug = str_replace('nr-', '', explode('/', $crawler->getUri())[4]);
            $lawTitle = "";
            $lawPrefix = "EG";
        } else {
            $headerSpan = $crawler->filter('h1.entry-title');
            // Get first span child of h1.entry-title
            $lawPrefixSlugLawBookTitle = $headerSpan->children()->first()->text();
            $lawPrefixSlugLawBookTitleArray = explode(' ', $lawPrefixSlugLawBookTitle);
            $lawPrefix = $lawPrefixSlugLawBookTitleArray[0];
            $lawSlug = $lawPrefixSlugLawBookTitleArray[1];
            $lawBookSlug = $lawPrefixSlugLawBookTitleArray[2];
            $lawBookTitle = $this->lawBookSlugToTitle($lawBookSlug);
            // Get second span child of h1.entry-title
            $lawTitle = $headerSpan->children()->eq(1)->text();
        }

        $lawUrl = $crawler->getUri();

        return new ParsedInformation(lawBookTitle: $lawBookTitle, lawBookSlug: $lawBookSlug, lawSlug: $lawSlug, lawPrefix: $lawPrefix, lawTitle: $lawTitle, lawUrl: $lawUrl);

    }

    private function lawBookSlugToTitle($slug): string {
        return match ($slug) {
            'DSGVO' => 'Datenschutz-Grundverordnung',
            'BDSG' => 'Bundesdatenschutzgesetz',
            'TTDSG' => 'Telekommunikations-Telemedien-Datenschutzgesetz',
            default => $slug,
        };
    }
}
