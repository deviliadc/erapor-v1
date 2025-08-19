<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;


// class ReusableExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
class ReusableExport implements FromArray, ShouldAutoSize, WithStyles
{
    /**
     * @return array
     */
    use Exportable;

    protected $headings;
    protected $enumInfo;
    protected $data;

    public function __construct(array $headings, array $enumInfo, array $data)
    {
        $this->headings = $headings;
        $this->enumInfo = $enumInfo;
        $this->data = $data;
    }

    // public function headings(): array
    // {
    //     return [
    //         $this->headings, // baris 1
    //         $this->enumInfo // baris 2
    //     ];
    // }

    // public function array(): array
    // {
    //     return $this->data;
    //     // return [
    //     //     $this->headings,   // baris 1
    //     //     $this->enumInfo,   // baris 2
    //     //     ...$this->data     // mulai baris 3 dst
    //     // ];
    // }

    public function array(): array
    {
        $rows = [$this->headings];

        if (!empty(array_filter($this->enumInfo))) {
            $rows[] = $this->enumInfo;
        }

        return array_merge($rows, $this->data);
    }


    // Tambahkan warna pada baris 1 dan 2
    public function styles(Worksheet $sheet)
    {
        $highestCol = $sheet->getHighestColumn();

        // Baris 1 - Heading utama
        $sheet->getStyle("A1:{$highestCol}1")->applyFromArray([
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'DDEBF7']
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Baris 2 - Enum info (jika ada)
        if (!empty(array_filter($this->enumInfo))) {
            $sheet->getStyle("A2:{$highestCol}2")->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'FFF2CC']
                ],
                'font' => [
                    'italic' => true,
                ],
            ]);
        }
    }

    public $sheetTitle = null;

    public function title(): string
    {
        return $this->sheetTitle ?? 'Sheet1';
    }
}
