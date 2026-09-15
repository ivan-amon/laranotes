<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPdf;
use App\Models\Page;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PagePdfExportController extends Controller
{
    /**
     * Queue a PDF export of the page.
     */
    public function store(Project $project, Page $page): JsonResponse
    {
        Gate::authorize('export', $page);

        $page->update([
            'export_status' => 'pending',
            'export_file' => null,
        ]);

        ProcessPdf::dispatch($page);

        return $this->exportStatus($page, 202);
    }

    /**
     * Get the status of the page's latest PDF export.
     */
    public function show(Project $project, Page $page): JsonResponse
    {
        Gate::authorize('export', $page);

        return $this->exportStatus($page);
    }

    /**
     * Build the export status response for the page.
     */
    protected function exportStatus(Page $page, int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => $page->export_status,
            'file' => $page->export_file,
        ], $status);
    }
}
