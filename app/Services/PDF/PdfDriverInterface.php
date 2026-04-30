<?php

namespace App\Services\PDF;

interface PdfDriverInterface
{
    /**
     * Generate a PDF from HTML content.
     *
     * @param string $html Rendered HTML
     * @param string $outputPath Full path to save the PDF
     * @param array $options Driver-specific options (format, margins, etc.)
     */
    public function generate(string $html, string $outputPath, array $options = []): void;
}
