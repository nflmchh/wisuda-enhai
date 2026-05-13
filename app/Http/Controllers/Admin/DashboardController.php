<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Participant;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Participant::count();
        $hadir = Participant::where('attendance_status', 'hadir')->count();
        $belumHadir = $total - $hadir;
        $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

        $wisudawanHadir = Participant::where('category', 'Wisudawan')->where('attendance_status', 'hadir')->count();
        $orangtuaHadir = Participant::where('category', 'Orang Tua')->where('attendance_status', 'hadir')->count();
        $tamuHadir = Participant::where('category', 'Tamu Lainnya')->where('attendance_status', 'hadir')->count();

        $recentScans = AttendanceLog::with('participant')
            ->orderByDesc('scanned_at')
            ->limit(20)
            ->get();

        return view('admin.dashboard', compact(
            'total', 'hadir', 'belumHadir', 'persentase',
            'wisudawanHadir', 'orangtuaHadir', 'tamuHadir',
            'recentScans'
        ));
    }
}
