<?php

namespace App\Services\PDF;

class PdfDriverFactory
{
    public static function make(?string $driver = null): PdfDriverInterface
    {
        $driver = $driver ?? config('gpro.pdf.driver', 'dompdf');

        return match ($driver) {
            'browsershot' => new BrowsershotDriver(),
            default => new DomPdfDriver(),
        };
    }
}
