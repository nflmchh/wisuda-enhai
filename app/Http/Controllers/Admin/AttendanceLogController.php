<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class AttendanceLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceLog::with('participant')->orderByDesc('scanned_at');

        if ($request->filled('gate')) {
            $query->where('scanner_gate', $request->gate);
        }
        if ($request->filled('status')) {
            $query->where('scan_status', $request->status);
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('admin.attendance-logs', compact('logs'));
    }
}
