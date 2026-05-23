<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportService
{
    public function stream(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {

            $handle = fopen('php://output', 'w');

            // optional UTF-8 BOM supaya enak dibuka Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // header csv
            fputcsv($handle, $headers);

            // isi data
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function streamSections(string $filename, array $sections): StreamedResponse
    {
        return response()->streamDownload(function () use ($sections) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            foreach ($sections as $index => $section) {
                if ($index > 0) {
                    fputcsv($handle, []);
                }

                if (!empty($section['title'])) {
                    fputcsv($handle, [$section['title']]);
                }

                fputcsv($handle, $section['headers']);

                foreach ($section['rows'] as $row) {
                    fputcsv($handle, $row);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
