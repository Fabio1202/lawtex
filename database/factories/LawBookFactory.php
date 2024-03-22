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
            ],
            [
                'name' => 'Strafgesetzbuch',
                'slug' => 'stgb',
            ],
            [
                'name' => 'Handelsgesetzbuch',
                'slug' => 'hgb',
            ],
        ];

        return $books[array_rand($books)];
    }
}
