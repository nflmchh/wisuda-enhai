<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvitationMail;
use App\Models\EmailLog;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailBlastController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Participant::whereNotNull('email')->where('email', '!=', '')->count(),
            'terkirim' => EmailLog::where('status', 'terkirim')->distinct('participant_id')->count(),
            'gagal' => EmailLog::where('status', 'gagal')
                ->whereNotIn('participant_id', EmailLog::where('status', 'terkirim')->pluck('participant_id'))
                ->distinct('participant_id')->count(),
            'belum' => Participant::whereNotIn('id', EmailLog::where('status', 'terkirim')->pluck('participant_id'))
                ->whereNotNull('email')->where('email', '!=', '')->count(),
        ];

        $logs = EmailLog::with('participant')->orderByDesc('created_at')->paginate(20);

        return view('admin.email-blast', compact('stats', 'logs'));
    }

    public function sendAll(Request $request)
    {
        $mode = $request->input('mode', 'belum');

        $query = Participant::whereNotNull('email')->where('email', '!=', '');

        if ($mode === 'belum') {
            $sentIds = EmailLog::where('status', 'terkirim')->pluck('participant_id');
            $query->whereNotIn('id', $sentIds);
        } elseif ($mode === 'gagal') {
            $failedIds = EmailLog::where('status', 'gagal')
                ->whereNotIn('participant_id', EmailLog::where('status', 'terkirim')->pluck('participant_id'))
                ->pluck('participant_id');
            $query->whereIn('id', $failedIds);
        }

        $participants = $query->get();
        $sent = 0;
        $failed = 0;

        foreach ($participants as $participant) {
            try {
                Mail::to($participant->email)->send(new InvitationMail($participant));

                EmailLog::create([
                    'participant_id' => $participant->id,
                    'email' => $participant->email,
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);
                $sent++;
            } catch (\Exception $e) {
                EmailLog::create([
                    'participant_id' => $participant->id,
                    'email' => $participant->email,
                    'status' => 'gagal',
                    'error_message' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        return back()->with('success', "Email terkirim: $sent, Gagal: $failed.");
    }

    public function sendOne(Participant $participant)
    {
        try {
            Mail::to($participant->email)->send(new InvitationMail($participant));
            EmailLog::create([
                'participant_id' => $participant->id,
                'email' => $participant->email,
                'status' => 'terkirim',
                'sent_at' => now(),
            ]);
            return back()->with('success', "Email berhasil dikirim ke {$participant->email}.");
        } catch (\Exception $e) {
            EmailLog::create([
                'participant_id' => $participant->id,
                'email' => $participant->email,
                'status' => 'gagal',
                'error_message' => $e->getMessage(),
            ]);
            return back()->with('error', "Gagal mengirim email: " . $e->getMessage());
        }
    }
}
