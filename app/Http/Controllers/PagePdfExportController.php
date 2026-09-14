<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Services\PdfProcessorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PagePdfExportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, Page $page, PdfProcessorService $pdfProcessorService)
    {
        Gate::authorize('export', $page);

        $fileName = $pdfProcessorService->generate($page->content);

        return response()->json([
            'message' => 'PDF Generated Successfully.',
            'file' => $fileName,
        ], 200);
    }
}
