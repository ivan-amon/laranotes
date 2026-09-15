<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\Project;
use App\Models\User;

class PagePolicy
{
    /**
     * The number of pages a project on the free tier may contain.
     *
     * A premium plan will lift this ceiling, so the limit is enforced here
     * rather than as a schema constraint.
     */
    public const int FREE_PAGE_LIMIT = 5;

    /**
     * Determine whether the user can list the pages of the project.
     */
    public function viewAny(User $user, Project $project): bool
    {
        return $this->ownsProject($user, $project);
    }

    /**
     * Determine whether the user can view the page.
     */
    public function view(User $user, Page $page): bool
    {
        return $this->ownsProject($user, $page->project);
    }

    /**
     * Determine whether the user can add another page to the project.
     */
    public function create(User $user, Project $project): bool
    {
        return $this->ownsProject($user, $project)
            && $project->pages()->count() < self::FREE_PAGE_LIMIT;
    }

    /**
     * Determine whether the user can update the page.
     */
    public function update(User $user, Page $page): bool
    {
        return $this->ownsProject($user, $page->project);
    }

    /**
     * Determine whether the user can delete the page.
     */
    public function delete(User $user, Page $page): bool
    {
        return $this->ownsProject($user, $page->project);
    }

    /**
     * Determine whether the user can export the page to PDF.
     */
    public function export(User $user, Page $page): bool
    {
        return $this->ownsProject($user, $page->project);
    }

    /**
     * Determine whether the user owns the project.
     */
    protected function ownsProject(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}
