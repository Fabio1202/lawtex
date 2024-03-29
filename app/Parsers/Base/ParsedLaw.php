<?php /** @noinspection LanguageDetectionInspection */

namespace App\Parsers\Base;

class ParsedLaw
{
    private string $prefix;

    private string $section;

    private string $title;

    private array $paragraphs;

    private string $url;

    private string $lawBookSlug;

    private string $lawBookTitle;

    private string $lawBookShort;

    /**
     * @param string $prefix
     * @param string $section
     * @param string $title
     * @param array $paragraphs
     * @param string $url
     * @param string $lawBookSlug
     * @param string $lawBookTitle
     */
    public function __construct(string $section, string $title, array $paragraphs, string $url, string $lawBookSlug, string $lawBookShort, string $lawBookTitle, string $prefix = '§')
    {
        $this->prefix = $prefix;
        $this->section = $section;
        $this->title = $title;
        $this->paragraphs = $paragraphs;
        $this->url = $url;
        $this->lawBookSlug = $lawBookSlug;
        $this->lawBookTitle = $lawBookTitle;
        $this->lawBookShort = $lawBookShort;
    }


    public function toLatex(): string
    {
        $str = '\noindent\fbox{\begin{minipage}{\textwidth}\vspace{0.5em}';
        $str .= '{\centering \textbf{\LARGE ' . $this->lawBookTitle . ' (' . $this->lawBookShort . ')} \\\\[.5em] \textbf{\LARGE ' . $this->prefix . ' ' . $this->section . ' ' . $this->title . '} \par}';
        $str .= '';
        if(count($this->paragraphs) > 1) {
            $str .= '\vspace{0.5em}\begin{enumerate}';
        } else {
            $str .= '\vspace{1em}';
        }
        foreach ($this->paragraphs as $i=>$paragraph) {
            $number = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
            $paragraph = str_replace("\n", ' \\\\ ', $paragraph);
            $paragraph = str_replace("<br>", ' \\\\ ', $paragraph);
            if(count($this->paragraphs) > 1)
                $str .= '\item ';
            $str .= '\abs' .  $number->format($i + 1) . 'style{' . $paragraph . '}';
        }
        if(count($this->paragraphs) > 1) {
            $str .= '\end{enumerate}\vspace{0.00em}';
        } else {
            $str .= '\vspace{1em}';
        }
        $str .= '\end{minipage}}';

        return $str;
    }

    public function getParagraphs(): array
    {
        return $this->paragraphs;
    }

    public function getLawBookSlug(): string
    {
        return $this->lawBookSlug;
    }

    public function getSection(): string
    {
        return $this->section;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLawBookTitle(): string
    {
        return $this->lawBookTitle;
    }

    public function getLawBookShort(): string
    {
        return $this->lawBookShort;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
