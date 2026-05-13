<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function __construct(private array $filters = []) {}

    public function collection(): Collection
    {
        $query = Participant::query();

        if (!empty($this->filters['status'])) {
            $query->where('attendance_status', $this->filters['status']);
        }
        if (!empty($this->filters['category'])) {
            $query->where('category', $this->filters['category']);
        }
        if (!empty($this->filters['faculty'])) {
            $query->where('faculty', $this->filters['faculty']);
        }
        if (!empty($this->filters['program_study'])) {
            $query->where('program_study', $this->filters['program_study']);
        }

        return $query->get()->map(fn($p) => [
            $p->name,
            $p->nrp,
            $p->email,
            $p->faculty,
            $p->program_study,
            $p->category,
            $p->attendance_status === 'hadir' ? 'Hadir' : 'Belum Hadir',
            $p->attended_at ? $p->attended_at->format('d/m/Y H:i:s') : '-',
            $p->scanner_gate ?? '-',
        ]);
    }

    public function headings(): array
    {
        return ['Nama', 'NRP', 'Email', 'Fakultas', 'Program Studi', 'Kategori', 'Status Hadir', 'Jam Hadir', 'Gate'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E3A5F']], 'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
