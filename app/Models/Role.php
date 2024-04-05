<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'description'];
    public static function boot(): void
    {
        parent::boot();

        /**
         * Restrict that name cannot be admin
         */
        static::saving(function ($model) {
            if ($model->name === 'admin') {
                throw new \Exception('Name cannot be admin');
            }
        });
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
