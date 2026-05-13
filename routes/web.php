<?php

use App\Http\Controllers\Admin\AttendanceLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisplaySettingController;
use App\Http\Controllers\Admin\EmailBlastController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScannerController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', fn() => redirect()->route('login'));

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Participants
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/{participant}', [ParticipantController::class, 'show'])->name('participants.show');
    Route::get('/participants/{participant}/edit', [ParticipantController::class, 'edit'])->name('participants.edit');
    Route::put('/participants/{participant}', [ParticipantController::class, 'update'])->name('participants.update');
    Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy'])->name('participants.destroy');
    Route::post('/participants/{participant}/reset-attendance', [ParticipantController::class, 'resetAttendance'])->name('participants.reset-attendance');
    Route::get('/participants/{participant}/download-qr', [ParticipantController::class, 'downloadQr'])->name('participants.download-qr');
    Route::post('/participants/{participant}/regenerate-qr', [ParticipantController::class, 'regenerateQr'])->name('participants.regenerate-qr');

    // Import
    Route::get('/import', [ImportController::class, 'index'])->name('import');
    Route::post('/import', [ImportController::class, 'store'])->name('import.store');
    Route::get('/import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');

    // Email Blast
    Route::get('/email-blast', [EmailBlastController::class, 'index'])->name('email-blast');
    Route::post('/email-blast/send', [EmailBlastController::class, 'sendAll'])->name('email-blast.send');
    Route::post('/email-blast/send/{participant}', [EmailBlastController::class, 'sendOne'])->name('email-blast.send-one');

    // Export
    Route::get('/export', [ExportController::class, 'index'])->name('export');
    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');

    // Attendance Logs
    Route::get('/attendance-logs', [AttendanceLogController::class, 'index'])->name('attendance-logs');

    // Display Settings
    Route::get('/display-settings', [DisplaySettingController::class, 'index'])->name('display-settings');
    Route::put('/display-settings', [DisplaySettingController::class, 'update'])->name('display-settings.update');
});

// Scanner routes (public — no login required)
Route::prefix('scanner')->name('scanner.')->group(function () {
    Route::get('/', [ScannerController::class, 'show'])->name('show');
    Route::post('/scan', [ScannerController::class, 'scan'])->name('scan');
});

