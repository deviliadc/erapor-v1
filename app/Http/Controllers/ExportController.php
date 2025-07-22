<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $modelClass = $request->input('model'); // Contoh: App\Models\User
        $columns = $request->input('columns'); // Contoh: ['name', 'email']
        $filename = $request->input('filename', 'export.xlsx');

        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Model tidak ditemukan.'], 400);
        }

        $data = $modelClass::select($columns)->get()->toArray();

        return Excel::download(new GenericExport($data, $columns), $filename);
    }
}
