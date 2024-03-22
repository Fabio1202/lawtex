<?php

namespace Database\Seeders;

use App\Models\Law;
use App\Models\Project;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            Project::factory()
                ->hasLaws(rand(1, 10))
                ->create();
        }
    }
}
