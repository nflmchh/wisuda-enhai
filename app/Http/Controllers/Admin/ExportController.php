<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ParticipantsExport;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $faculties = Participant::distinct()->pluck('faculty')->filter()->sort()->values();
        return view('admin.export', compact('faculties'));
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['status', 'category', 'faculty', 'program_study']);
        $filename = 'data-peserta-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new ParticipantsExport($filters), $filename);
    }

    public function exportPdf(Request $request)
    {
        $query = Participant::query();

        if ($request->filled('status')) $query->where('attendance_status', $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('faculty')) $query->where('faculty', $request->faculty);

        $participants = $query->orderBy('name')->get();
        $filters = $request->only(['status', 'category', 'faculty']);

        $pdf = Pdf::loadView('admin.exports.pdf', compact('participants', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-peserta-' . now()->format('Ymd-His') . '.pdf');
    }
}
