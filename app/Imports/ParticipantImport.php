<?php

namespace App\Imports;

use App\Models\Participant;
use App\Services\QrCodeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ParticipantImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $imported = 0;
    public int $skipped = 0;

    private QrCodeService $qrService;

    public function __construct()
    {
        $this->qrService = new QrCodeService();
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            $name = trim($row['name'] ?? $row['nama'] ?? '');
            $nrp = trim($row['nrp'] ?? $row['nim'] ?? '');
            $email = trim($row['email'] ?? '');
            $faculty = trim($row['faculty'] ?? $row['fakultas'] ?? '');
            $programStudy = trim($row['program_study'] ?? $row['prodi'] ?? $row['program_studi'] ?? '');
            $category = trim($row['category'] ?? $row['kategori'] ?? 'Wisudawan');

            if (empty($name) || empty($nrp)) {
                $this->errors[] = "Baris $rowNum: Nama dan NRP wajib diisi.";
                $this->skipped++;
                continue;
            }

            if (!in_array($category, ['Wisudawan', 'Orang Tua', 'Tamu Lainnya'])) {
                $category = 'Wisudawan';
            }

            if (Participant::where('nrp', $nrp)->exists()) {
                $this->errors[] = "Baris $rowNum: NRP '$nrp' sudah ada (di-skip).";
                $this->skipped++;
                continue;
            }

            $token = Str::random(32);

            $participant = Participant::create([
                'name' => $name,
                'nrp' => $nrp,
                'email' => $email,
                'faculty' => $faculty,
                'program_study' => $programStudy,
                'category' => $category,
                'qr_token' => $token,
                'attendance_status' => 'belum_hadir',
            ]);

            try {
                $this->qrService->generateQrCode($participant);
            } catch (\Exception $e) {
                // QR generation failure is non-fatal
            }

            $this->imported++;
        }
    }
}
