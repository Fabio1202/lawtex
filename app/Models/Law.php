<?php

namespace App\Models;

use App\Models\Scopes\OrderedBySlugScope;
use App\Parsers\Base\ParsedInformation;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([OrderedBySlugScope::class])]
class Law extends Model
{
    use HasFactory;

    // Has LawBook
    public function lawBook(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LawBook::class);
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
