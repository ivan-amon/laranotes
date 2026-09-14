<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Display a listing of the project's pages.
     */
    public function index(Project $project): Response
    {
        Gate::authorize('viewAny', [Page::class, $project]);

        return Inertia::render('projects/pages/Index', [
            'project' => $project,
            'pages' => $project->pages()->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new page.
     */
    public function create(Project $project): Response
    {
        Gate::authorize('create', [Page::class, $project]);

        return Inertia::render('projects/pages/Create', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created page in the project.
     */
    public function store(StorePageRequest $request, Project $project): RedirectResponse
    {
        $project->pages()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Page created.')]);

        return to_route('projects.pages.index', $project);
    }

    /**
     * Display the specified page.
     */
    public function show(Project $project, Page $page): Response
    {
        Gate::authorize('view', $page);

        return Inertia::render('projects/pages/Show', [
            'project' => $project,
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Project $project, Page $page): Response
    {
        Gate::authorize('update', $page);

        return Inertia::render('projects/pages/Edit', [
            'project' => $project,
            'page' => $page,
        ]);
    }

    /**
     * Update the specified page.
     */
    public function update(UpdatePageRequest $request, Project $project, Page $page): RedirectResponse
    {
        $page->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Page updated.')]);

        return to_route('projects.pages.show', [$project, $page]);
    }

    /**
     * Remove the specified page from the project.
     */
    public function destroy(Project $project, Page $page): RedirectResponse
    {
        Gate::authorize('delete', $page);

        $page->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Page deleted.')]);

        return to_route('projects.pages.index', $project);
    }
}
