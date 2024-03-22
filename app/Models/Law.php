<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Law extends Model
{
    use HasFactory;

    // Has LawBook
    public function lawBook(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LawBook::class);
    }
}
