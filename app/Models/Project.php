<?php

namespace App\Models;

use App\Parsers\Base\LawParser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;

class Project extends Model
{
    use HasFactory, HasUuids;

    // Has User
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Has Many Laws
    public function laws(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Law::class);
    }

    public function lawBooks(): \Illuminate\Support\Collection
    {
        // No direct connection, only through laws
        return $this->laws->pluck('lawBook')->unique();
    }

    /**
     * To latex file
     */
    public function toLatex(): string
    {
        if($this->laws->isEmpty()) {
            return '';
        }

        $parser = new LawParser();
        $parsedLaws = $this->laws->map(function ($law) use ($parser) {
            return $parser->fullParse($law);
        });

        $max = max($parsedLaws->map(fn($parsed) => count($parsed->getParagraphs()))->toArray());

        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);

        $formatterOrdinal = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $formatterOrdinal->setTextAttribute(NumberFormatter::DEFAULT_RULESET,
            "%spellout-ordinal");

        // Start building the latex string

        $latex = '
            \usepackage{xifthen}
            \usepackage{setspace}
            \usepackage{enumitem}
            \newcommand{\ifequals}[3]{\ifthenelse{\equal{#1}{#2}}{#3}{}}
            \newcommand{\case}[2]{#1 #2}
            \newenvironment{switch}[1]{\renewcommand{\case}{\ifequals{#1}}}{}
            \newcommand{\absbasestyle}[1]{#1}
        ';

        for ($i = 0; $i < $max; $i++) {
            $latex .=
                '\newcommand{\abs' .
                $formatter->format($i + 1) .
                'style}[1]{\absbasestyle{#1}}';
        }

        for ($i = 0; $i < $max; $i++) {
            $latex .=
                '\newcommand{\mark' .
                $formatterOrdinal->format($i + 1) .
                'abs}{\renewcommand{\absbasestyle}[1]{{\color{gray} ##1}}\renewcommand{\abs' .
                $formatter->format($i + 1) . 'style}[1]{\textbf{##1}}}';
        }

        $latex .= '\newcommand{\law}[2][]{
            {
                #1
                \begin{switch}{#2}
                    \renewcommand\labelenumi{(\theenumi)}';

        foreach ($parsedLaws as $parsedLaw) {
            $latex .=
                '\case{' .
                $parsedLaw->getLawBookSlug() .
                ':' .
                $parsedLaw->getSection() .
                '}{';
            $latex .= $parsedLaw->toLatex();
            $latex .= '}';
        }

        $latex .= '\end{switch}
            }
        }';

        $latex .= '\newcommand\tableoflaws{
            \section*{Gesetze}';

        // Group parsed laws by law book
        $parsedLawsByLawBook = $parsedLaws->groupBy(fn($parsed) => $parsed->getLawBookSlug());

        foreach ($parsedLawsByLawBook as $lawBookSlug => $parsedLaws) {
            $lawBook = $parsedLaws->first()->getLawBookTitle();
            $lawBookShort = $parsedLaws->first()->getLawBookShort();
            $latex .= '\subsection*{' . $lawBook . ' - ' . $lawBookShort . '}';
            $latex .= '\begin{itemize}[leftmargin=*, label={}]';
            foreach ($parsedLaws as $parsedLaw) {
                $latex .= '\item[] ' .
                    $parsedLaw->getPrefix() .
                    ' ' .
                    $parsedLaw->getSection();
                if ($parsedLaw->getTitle() !== '') {
                    $latex .= ' - ' . $parsedLaw->getTitle();
                }
            }
            $latex .= '\end{itemize}';
        }
        $latex .= '}';

        return str_replace("\n", '', str_replace('    ', '', $latex));
        /*
         *

\newcommand\tableoflaws{
    \section*{Gesetze}
    \subsection*{Bürgerliches Gesetzbuch - BGB}
    \begin{itemize}[leftmargin=*, label={}]
        \item[] § 164 - Wirkung der Erklärung des Vertreters
    \end{itemize}
}
         */
    }
}
