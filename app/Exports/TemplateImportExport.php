<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateImportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection(): Collection
    {
        return collect([
            ['Budi Santoso', '2024001', 'budi@example.com', 'Teknik', 'Teknik Informatika', 'Wisudawan'],
            ['Sari Dewi', '2024002', 'sari@example.com', 'Ekonomi', 'Manajemen', 'Orang Tua'],
        ]);
    }

    public function headings(): array
    {
        return ['name', 'nrp', 'email', 'faculty', 'program_study', 'category'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E3A5F']], 'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
