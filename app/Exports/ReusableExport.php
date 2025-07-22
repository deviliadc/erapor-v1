<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReusableExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * @return array
     */
    protected $headings;
    protected $data;

    public function __construct(array $headings, array $data)
    {
        $this->headings = $headings;
        $this->data = $data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold heading
        ];
    }
}
