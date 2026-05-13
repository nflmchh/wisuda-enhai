<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Services\QrCodeService;
use App\Services\QrPngService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    public function __construct(private QrCodeService $qrService) {}

    public function index(Request $request)
    {
        $query = Participant::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('nrp', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('attendance_status', $request->status);
        }
        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        $participants = $query->orderByDesc('created_at')->paginate(25)->withQueryString();
        $faculties = Participant::distinct()->pluck('faculty')->filter()->sort()->values();

        return view('admin.participants.index', compact('participants', 'faculties'));
    }

    public function show(Participant $participant)
    {
        return view('admin.participants.show', compact('participant'));
    }

    public function edit(Participant $participant)
    {
        return view('admin.participants.edit', compact('participant'));
    }

    public function update(Request $request, Participant $participant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nrp' => 'required|string|max:50|unique:participants,nrp,' . $participant->id,
            'email' => 'nullable|email|max:255',
            'faculty' => 'nullable|string|max:255',
            'program_study' => 'nullable|string|max:255',
            'category' => 'required|in:Wisudawan,Orang Tua,Tamu Lainnya',
        ]);

        $participant->update($data);
        return redirect()->route('admin.participants.show', $participant)->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();
        return redirect()->route('admin.participants.index')->with('success', 'Peserta berhasil dihapus.');
    }

    public function resetAttendance(Participant $participant)
    {
        $participant->update([
            'attendance_status' => 'belum_hadir',
            'attended_at' => null,
            'scanner_gate' => null,
        ]);
        return back()->with('success', 'Status kehadiran berhasil direset.');
    }

    public function downloadQr(Participant $participant)
    {
        // GD-based PNG (no imagick needed) embedded as base64 data URI
        $qrPng = QrPngService::generateBase64($participant->qr_token, 400);

        $pdf = Pdf::loadView('pdf.invitation', compact('participant', 'qrPng'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($participant->nrp . '-undangan-wisuda.pdf');
    }

    public function regenerateQr(Participant $participant)
    {
        $participant->update(['qr_token' => Str::random(32)]);
        $this->qrService->generateQrCode($participant);
        return back()->with('success', 'QR Code berhasil digenerate ulang.');
    }
}
