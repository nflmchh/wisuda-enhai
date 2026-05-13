<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TemplateImportExport;
use App\Http\Controllers\Controller;
use App\Imports\ParticipantImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        $import = new ParticipantImport();
        Excel::import($import, $request->file('file'));

        $message = "Import selesai. Berhasil: {$import->imported} peserta, Dilewati: {$import->skipped} baris.";

        return redirect()->route('admin.import')->with([
            'success' => $message,
            'import_errors' => $import->errors,
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateImportExport(), 'template-import-peserta.xlsx');
    }
}
