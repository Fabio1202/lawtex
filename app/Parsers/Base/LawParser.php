<?php

namespace App\Parsers\Base;

use App\Models\Law;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class LawParser
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws ParserNotFoundException
     */
    public function fullParse(Law $law): ParsedLaw
    {
        $crawler = $this->getCrawler($law->url);
        $parser = $this->getMatchingParser($law->url);

        return $parser->fullParse($law, $crawler);
    }

    /**
     * @throws ParserNotFoundException
     */
    public function parseInformation(string $url): ParsedInformation
    {
        $crawler = $this->getCrawler($url);
        $parser = $this->getMatchingParser($url);

        return $parser->parseInformation($crawler);
    }

    /**
     * @throws ParserNotFoundException if no parser is found for the given url
     */
    private function getMatchingParser(string $url): ParserInterface
    {
        // $url should be a string like 'gesetze-im-internet.de'
        $url = parse_url($url)['host']; // 'gesetze-im-internet.de
        $url = str_replace('www.', '', $url); // 'gesetze-im-internet.de'
        $startUrl = $url;
        $parser = config('parser.parser_classes');

        if (isset($parser[$url])) {
            return app($parser[$url]);
        }

        $explode = explode('.', $url, 2);

        while (count($explode) > 1) {
            $url = $explode[1];
            if (isset($parser[$url])) {
                return app($parser[$url]);
            }
            $explode = explode('.', $url, 2);
        }

        throw new ParserNotFoundException($startUrl);
    }

    private function getCrawler(string $url): Crawler
    {
        $response = $this->client->request('GET', $url);

        return new Crawler($response->getBody());
    }
}
