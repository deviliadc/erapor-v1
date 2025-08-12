<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class ReusableImport implements ToCollection, WithHeadingRow
// {
//     protected $callback;

//     public function __construct(callable $callback)
//     {
//         $this->callback = $callback;
//     }

//     public function collection(Collection $rows)
//     {
//         foreach ($rows as $i => $row) {
//             call_user_func($this->callback, $row, $i);
//         }
//     }
// }
class ReusableImport implements ToCollection
{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            // $row adalah Collection berisi data per kolom, index 0,1,2,...
            call_user_func($this->callback, $row, $i);
        }
    }
}
