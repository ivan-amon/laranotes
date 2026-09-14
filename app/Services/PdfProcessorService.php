<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Sleep;

class PdfProcessorService
{
    public function generate(string $page): string
    {
        Log::info('[PDF Service] Starting export...');

        // 1. Simulate parsing text from MySQL and loading fonts (1-2 seconds)
        $parsingTime = random_int(1, 2);
        Log::info('[PDF Service] Parsing text from MySQL and loading fonts...');
        Sleep::for($parsingTime)->seconds();

        // 2. Simulate rendering and layout of the pages (3 seconds)
        $renderingTime = random_int(3, 5);
        Log::info('[PDF Service] Rendering pages...');
        Sleep::for($renderingTime)->seconds();

        // 3. Simulate writing to disk
        $fileName = 'export_'.time().'.pdf';
        $totalTime = $parsingTime + $renderingTime;
        Log::info("[PDF Service] ✅ success in {$totalTime} seconds: {$fileName}");

        return $fileName;
    }
}
