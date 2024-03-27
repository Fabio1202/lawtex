<?php

namespace App\Parsers\Base;

use App\Models\Law;
use App\Models\LawBook;

class ParsedInformation
{
    protected string $lawBookTitle;

    protected string $lawBookSlug;

    protected string $lawSlug;

    protected string $lawPrefix;

    protected string $lawTitle;

    protected string $lawUrl;

    /**
     * @param string $lawBookTitle
     * @param string $lawBookSlug
     * @param string $lawSlug
     * @param string $lawPrefix
     * @param string $lawTitle
     * @param string $lawUrl
     */
    public function __construct(string $lawBookTitle, string $lawBookSlug, string $lawSlug, string $lawPrefix, string $lawTitle, string $lawUrl)
    {
        $this->lawBookTitle = $lawBookTitle;
        $this->lawBookSlug = $lawBookSlug;
        $this->lawSlug = $lawSlug;
        $this->lawPrefix = $lawPrefix;
        $this->lawTitle = $lawTitle;
        $this->lawUrl = $lawUrl;
    }


    public function toLaw(): Law
    {
        $law = new Law();
        $law->name = $this->lawTitle;
        $law->slug = $this->lawSlug;
        $law->url = $this->lawUrl;
        $lawBook = LawBook::firstOrCreate([
            'slug' => strtolower($this->lawBookSlug),
        ], [
            'short' => $this->lawBookSlug,
            'prefix' => $this->lawPrefix,
            'name' => $this->lawBookTitle,
        ]);
        $law->lawBook()->associate($lawBook);

        return $law;
    }
}
