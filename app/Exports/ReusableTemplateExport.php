<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReusableTemplateExport implements FromArray, WithStyles
{
    protected $headers;

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function array(): array
    {
        return [$this->headers];
    }

    // Tambahkan warna pada baris 1 dan 2
    public function styles(Worksheet $sheet)
    {
        $rowCount = count($this->headers);
        // Baris 1 (header utama)
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'DDEBF7'] // biru muda
            ],
            'font' => [
                'bold' => true,
            ],
        ]);
        // Baris 2 (keterangan enum)
        if ($rowCount > 1) {
            $sheet->getStyle('A2:' . $sheet->getHighestColumn() . '2')->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'FFF2CC'] // kuning muda
                ],
                'font' => [
                    'italic' => true,
                ],
            ]);
        }
    }
}
