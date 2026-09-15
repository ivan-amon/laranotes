<?php

namespace App\Jobs;

use App\Models\Page;
use App\Services\PdfProcessorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessPdf implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Page $page) {}

    /**
     * Execute the job.
     */
    public function handle(PdfProcessorService $pdfProcessor): void
    {
        $this->page->update([
            'export_status' => 'processing',
        ]);

        $fileName = $pdfProcessor->generate($this->page->content);

        $this->page->update([
            'export_status' => 'completed',
            'export_file' => $fileName,
        ]);
    }

    /**
     * Mark the export as failed so the client stops polling.
     */
    public function failed(?Throwable $exception): void
    {
        $this->page->update([
            'export_status' => 'failed',
        ]);
    }
}
