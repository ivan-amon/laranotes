<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Seed a project for every user that does not have one yet.
     */
    public function run(): void
    {
        User::doesntHave('projects')
            ->each(fn (User $user) => Project::factory()->for($user)->create());
    }
}
