<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawBook extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Has Many Laws
    public function laws(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Law::class);
    }
}
