<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * The number of projects a user on the free tier may own.
     *
     * A premium plan will raise this ceiling, so the limit is enforced here
     * rather than as a schema constraint.
     */
    public const int FREE_PROJECT_LIMIT = 1;

    /**
     * Determine whether the user can list their own projects.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        return $this->owns($user, $project);
    }

    /**
     * Determine whether the user can create another project.
     */
    public function create(User $user): bool
    {
        return $user->projects()->count() < self::FREE_PROJECT_LIMIT;
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $this->owns($user, $project);
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $this->owns($user, $project);
    }

    /**
     * Determine whether the user owns the project.
     */
    protected function owns(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}
