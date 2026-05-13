<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Participant;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        $token = trim($request->input('token', ''));
        $gate = $request->input('gate', '1');

        if (empty($token)) {
            return response()->json(['status' => 'invalid', 'message' => 'Token kosong.'], 422);
        }

        $participant = Participant::where('qr_token', $token)->first();

        if (!$participant) {
            AttendanceLog::create([
                'qr_token' => $token,
                'scanner_gate' => $gate,
                'scan_status' => 'invalid',
                'message' => 'QR / Barcode Tidak Valid',
                'scanned_at' => now(),
            ]);
            return response()->json(['status' => 'invalid', 'message' => 'QR / Barcode Tidak Valid']);
        }

        if ($participant->attendance_status === 'hadir') {
            return response()->json([
                'status' => 'duplicate',
                'message' => 'Sudah Pernah Absen',
                'name' => $participant->name,
                'nrp' => $participant->nrp,
            ]);
        }

        $now = now();
        $participant->update(['attendance_status' => 'hadir', 'attended_at' => $now, 'scanner_gate' => $gate]);

        AttendanceLog::create([
            'participant_id' => $participant->id,
            'qr_token' => $token,
            'scanner_gate' => $gate,
            'scan_status' => 'success',
            'message' => 'Berhasil Absen',
            'scanned_at' => $now,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Absen',
            'name' => $participant->name,
            'nrp' => $participant->nrp,
            'category' => $participant->category,
            'attended_at' => $now->format('d/m/Y H:i:s'),
        ]);
    }
}
