<?php

namespace App\Parsers\Base;

use App\Models\Law;

trait FullLawParseFromLaw
{
    function autoFullParse(Law $law, $paragraphs): ParsedLaw
    {
        $lawBookSlug = $law->lawBook->slug;
        $lawBookShort = $law->lawBook->short;
        $lawBookTitle = $law->lawBook->name;
        $prefix = $law->lawBook->prefix;

        $title = $law->name;
        $section = $law->slug;

        $url = $law->url;

        return new ParsedLaw($section, $title, $paragraphs, $url, $lawBookSlug, $lawBookShort,$lawBookTitle, $prefix);
    }
}
