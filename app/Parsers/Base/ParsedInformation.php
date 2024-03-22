<?php

namespace App\Parsers\Base;

use App\Models\Law;
use App\Models\LawBook;

class ParsedInformation
{
    public string $lawBookTitle;

    public string $lawBookSlug;

    public string $lawSlug;

    public string $lawPrefix;

    public string $lawTitle;

    public string $lawUrl;

    public function toLaw(): Law
    {
        $law = new Law();
        $law->name = $this->lawTitle;
        $law->slug = $this->lawSlug;
        $law->url = $this->lawUrl;
        $lawBook = LawBook::firstOrCreate([
            'name' => $this->lawBookTitle,
            'slug' => strtolower($this->lawBookSlug),
            'short' => $this->lawBookSlug,
        ]);
        $law->lawBook()->associate($lawBook);

        return $law;
    }
}
