<?php

namespace App\Services\PDF;

use Barryvdh\DomPDF\Facade\Pdf;

class DomPdfDriver implements PdfDriverInterface
{
    public function generate(string $html, string $outputPath, array $options = []): void
    {
        $format = $options['format'] ?? 'A4';
        $orientation = $options['orientation'] ?? 'portrait';

        $pdf = Pdf::loadHTML($html)
            ->setPaper($format, $orientation)
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('defaultFont', 'sans-serif');

        $directory = dirname($outputPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($outputPath, $pdf->output());
    }
}
