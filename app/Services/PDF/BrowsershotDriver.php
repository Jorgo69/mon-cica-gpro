<?php

namespace App\Services\PDF;

use Spatie\Browsershot\Browsershot;

class BrowsershotDriver implements PdfDriverInterface
{
    public function generate(string $html, string $outputPath, array $options = []): void
    {
        $format = $options['format'] ?? 'A4';

        $directory = dirname($outputPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $browsershot = Browsershot::html($html)
            ->format($format)
            ->noSandbox()
            ->margins(0, 0, 0, 0)
            ->waitUntilNetworkIdle();

        // Use system Chromium if available
        $chromePaths = ['/snap/bin/chromium', '/usr/bin/chromium-browser', '/usr/bin/google-chrome'];
        foreach ($chromePaths as $path) {
            if (file_exists($path)) {
                $browsershot->setChromePath($path);
                break;
            }
        }

        $browsershot->save($outputPath);
    }
}
