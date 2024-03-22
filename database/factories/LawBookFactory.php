<?php

namespace Database\Factories;

use App\Models\LawBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class LawBookFactory extends Factory
{
    protected $model = LawBook::class;

    public function definition(): array
    {
        $books = [
            [
                'name' => 'Bürgerliches Gesetzbuch',
                'slug' => 'bgb',
                'short' => 'BGB',
            ],
            [
                'name' => 'Strafgesetzbuch',
                'slug' => 'stgb',
                'short' => 'StGB',
            ],
            [
                'name' => 'Handelsgesetzbuch',
                'slug' => 'hgb',
                'short' => 'HGB',
            ],
        ];

        return $books[array_rand($books)];
    }
}
