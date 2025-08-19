<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReusableMultiSheetExport implements WithMultipleSheets
{
     use Exportable;
    protected $sheets;
    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function sheets(): array
    {
        $result = [];
        foreach ($this->sheets as $sheetName => $sheetData) {
            $result[] = new ReusableExport(
                $sheetData['headings'],
                $sheetData['enumInfo'] ?? [],
                $sheetData['data']
            );
            // Set sheet title
            $result[count($result)-1]->sheetTitle = $sheetName;
        }
        return $result;
    }
}
