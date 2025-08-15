<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;

class ReusableExportPdf
{
    protected $headings;
    protected $rows;
    protected $title;

    public function __construct($headings, $rows, $title = 'Export Data')
    {
        $this->headings = $headings;
        $this->rows = $rows;
        $this->title = $title;
    }

    public function download($filename = 'export.pdf')
    {
        $pdf = Pdf::loadView('exports.reusable-pdf', [
            'headings' => $this->headings,
            'rows' => $this->rows,
            'title' => $this->title,
        ]);
        return $pdf->download($filename);
    }
}
