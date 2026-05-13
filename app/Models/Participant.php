<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    protected $fillable = [
        'name', 'nrp', 'email', 'faculty', 'program_study', 'category',
        'qr_token', 'qr_code_path', 'barcode_path',
        'attendance_status', 'attended_at', 'scanner_gate',
    ];

    protected function casts(): array
    {
        return [
            'attended_at' => 'datetime',
        ];
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    public function isPresent(): bool
    {
        return $this->attendance_status === 'hadir';
    }

    public function latestEmailLog()
    {
        return $this->emailLogs()->latest()->first();
    }
}
