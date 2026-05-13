<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\DisplaySetting;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScannerController extends Controller
{
    public function show()
    {
        $setting = DisplaySetting::global();
        return view('scanner.show', compact('setting'));
    }

    public function scan(Request $request)
    {
        $token = trim($request->input('token', ''));
        $gate  = trim($request->input('gate', 'Utama')) ?: 'Utama';

        if (empty($token)) {
            return response()->json(['status' => 'invalid', 'message' => 'Token kosong.']);
        }

        return DB::transaction(function () use ($token, $gate) {
            $participant = Participant::where('qr_token', $token)->lockForUpdate()->first();

            if (!$participant) {
                AttendanceLog::create([
                    'participant_id' => null,
                    'qr_token'       => $token,
                    'scanner_gate'   => $gate,
                    'scan_status'    => 'invalid',
                    'message'        => 'QR / Barcode Tidak Valid',
                    'scanned_at'     => now(),
                ]);

                return response()->json([
                    'status'  => 'invalid',
                    'message' => 'QR / Barcode Tidak Valid',
                ]);
            }

            if ($participant->attendance_status === 'hadir') {
                AttendanceLog::create([
                    'participant_id' => $participant->id,
                    'qr_token'       => $token,
                    'scanner_gate'   => $gate,
                    'scan_status'    => 'duplicate',
                    'message'        => 'Sudah Pernah Absen pada ' . $participant->attended_at->format('d/m/Y H:i:s'),
                    'scanned_at'     => now(),
                ]);

                return response()->json([
                    'status'      => 'duplicate',
                    'message'     => 'Sudah Pernah Absen',
                    'name'        => $participant->name,
                    'nrp'         => $participant->nrp,
                    'category'    => $participant->category,
                    'attended_at' => $participant->attended_at->format('d/m/Y H:i:s'),
                ]);
            }

            $now = now();
            $participant->update([
                'attendance_status' => 'hadir',
                'attended_at'       => $now,
                'scanner_gate'      => $gate,
            ]);

            AttendanceLog::create([
                'participant_id' => $participant->id,
                'qr_token'       => $token,
                'scanner_gate'   => $gate,
                'scan_status'    => 'success',
                'message'        => 'Berhasil Absen',
                'scanned_at'     => $now,
            ]);

            return response()->json([
                'status'      => 'success',
                'message'     => 'Berhasil Absen',
                'name'        => $participant->name,
                'nrp'         => $participant->nrp,
                'category'    => $participant->category,
                'attended_at' => $now->format('d/m/Y H:i:s'),
            ]);
        });
    }
}
