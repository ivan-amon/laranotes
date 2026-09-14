<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Seed a few pages for every project that does not have any yet.
     */
    public function run(): void
    {
        Project::doesntHave('pages')
            ->each(fn (Project $project) => Page::factory()->count(3)->for($project)->create());
    }
}
