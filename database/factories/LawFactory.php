<?php

namespace Database\Factories;

use App\Models\Law;
use App\Models\LawBook;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class LawFactory extends Factory
{
    protected $model = Law::class;

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

        return [
            'law_book_id' => LawBook::firstOrCreate($books[array_rand($books)]),
            'name' => $this->faker->name(),
            'slug' => $this->faker->randomNumber(3),
            'url' => 'https://www.gesetze-im-internet.de/bgb/__823.html',
            'project_id' => Project::factory(),
        ];
    }
}
