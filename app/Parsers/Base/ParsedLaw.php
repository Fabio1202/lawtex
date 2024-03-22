<?php

namespace App\Parsers\Base;

class ParsedLaw
{
    public string $prefix = '§';

    public string $section;

    public string $title;

    public array $paragraphs;

    public string $url;

    public string $lawBookSlug;

    public string $lawBookTitle;

    public function toLatex(): string
    {
        return '';
    }
}
