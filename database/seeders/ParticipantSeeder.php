<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Services\QrCodeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $faculties = ['Teknik', 'Ekonomi', 'Hukum', 'Kedokteran', 'FISIP', 'Pertanian'];
        $programs = ['Teknik Informatika', 'Manajemen', 'Ilmu Hukum', 'Kedokteran Umum', 'Ilmu Komunikasi', 'Agribisnis'];
        $categories = ['Wisudawan', 'Orang Tua', 'Tamu Lainnya'];
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gilang', 'Hana', 'Irwan', 'Joko',
                       'Karina', 'Luthfi', 'Maya', 'Naufal', 'Oki', 'Putri', 'Rizal', 'Sari', 'Taufik', 'Umi'];
        $lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Hidayat', 'Rahmawati', 'Nugroho', 'Saputra',
                      'Permata', 'Suryadi', 'Lestari', 'Handoko', 'Setiawan', 'Purnama', 'Wibowo'];

        $qrService = new QrCodeService();

        for ($i = 1; $i <= 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = "$firstName $lastName";
            $nrp = '2024' . str_pad($i, 5, '0', STR_PAD_LEFT);
            $faculty = $faculties[array_rand($faculties)];
            $program = $programs[array_rand($programs)];
            $category = $i <= 70 ? 'Wisudawan' : ($i <= 90 ? 'Orang Tua' : 'Tamu Lainnya');
            $token = Str::random(32);

            $participant = Participant::create([
                'name' => $name,
                'nrp' => $nrp,
                'email' => strtolower(str_replace(' ', '.', $name)) . $i . '@example.com',
                'faculty' => $faculty,
                'program_study' => $program,
                'category' => $category,
                'qr_token' => $token,
                'attendance_status' => 'belum_hadir',
            ]);

            try {
                $qrService->generateForParticipant($participant);
            } catch (\Exception $e) {
                // Skip QR generation errors in seeder
            }
        }
    }
}
