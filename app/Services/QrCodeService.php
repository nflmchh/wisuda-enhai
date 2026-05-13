<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateForParticipant(Participant $participant): void
    {
        $this->generateQrCode($participant);
    }

    public function generateQrCode(Participant $participant): string
    {
        $dir = 'qrcodes';
        $filename = $dir . '/' . $participant->qr_token . '.svg';

        Storage::disk('public')->makeDirectory($dir);

        $qr = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($participant->qr_token);

        Storage::disk('public')->put($filename, $qr);

        $participant->update(['qr_code_path' => $filename]);

        return $filename;
    }

    public function generateAll(): int
    {
        $count = 0;
        Participant::whereNull('qr_code_path')
            ->orWhere('qr_code_path', '')
            ->chunk(50, function ($participants) use (&$count) {
                foreach ($participants as $p) {
                    $this->generateQrCode($p);
                    $count++;
                }
            });

        return $count;
    }

    public function getQrCodeUrl(Participant $participant): string
    {
        if ($participant->qr_code_path && Storage::disk('public')->exists($participant->qr_code_path)) {
            return Storage::disk('public')->url($participant->qr_code_path);
        }

        $this->generateQrCode($participant);
        return Storage::disk('public')->url($participant->qr_code_path);
    }

    public function getQrCodeSvg(Participant $participant): string
    {
        if ($participant->qr_code_path && Storage::disk('public')->exists($participant->qr_code_path)) {
            return Storage::disk('public')->get($participant->qr_code_path);
        }

        $this->generateQrCode($participant);
        return Storage::disk('public')->get($participant->qr_code_path);
    }
}
