@extends('layouts.admin')

@section('title', 'Detail Peserta')
@section('header', 'Detail Peserta')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">{{ $participant->name }}</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.participants.edit', $participant) }}"
                   class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition-colors">Edit</a>
                <form method="POST" action="{{ route('admin.participants.reset-attendance', $participant) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-lg text-xs hover:bg-yellow-100 transition-colors"
                            onclick="return confirm('Reset status kehadiran?')">
                        Reset Absen
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.participants.destroy', $participant) }}" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100 transition-colors"
                            onclick="return confirm('Hapus peserta ini?')">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="px-6 py-5 grid grid-cols-2 gap-4">
            @foreach([
                ['NRP', $participant->nrp],
                ['Email', $participant->email ?: '-'],
                ['Kategori', $participant->category],
                ['Fakultas', $participant->faculty ?: '-'],
                ['Program Studi', $participant->program_study ?: '-'],
                ['Status Kehadiran', $participant->attendance_status === 'hadir' ? 'Hadir' : 'Belum Hadir'],
                ['Jam Hadir', $participant->attended_at ? $participant->attended_at->format('d/m/Y H:i:s') : '-'],
                ['Gate Scanner', $participant->scanner_gate ? 'Gate '.$participant->scanner_gate : '-'],
            ] as [$label, $value])
            <div>
                <p class="text-xs text-gray-500 font-medium">{{ $label }}</p>
                <p class="text-sm text-gray-800 mt-0.5">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- QR Code --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h4 class="font-semibold text-gray-700 mb-4">QR Code Invitation</h4>
        <div class="flex items-center gap-6">
            @if($participant->qr_code_path)
                <div class="w-32 h-32 border border-gray-200 rounded-lg p-2 flex items-center justify-center">
                    <img src="{{ asset('storage/' . $participant->qr_code_path) }}" alt="QR Code" class="w-full h-full object-contain">
                </div>
            @else
                <div class="w-32 h-32 border-2 border-dashed border-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs text-center p-2">
                    QR belum digenerate
                </div>
            @endif
            <div class="space-y-2">
                <p class="text-xs text-gray-500">Token: <span class="font-mono text-gray-700">{{ $participant->qr_token }}</span></p>
                <div class="flex gap-2">
                    <a href="{{ route('admin.participants.download-qr', $participant) }}"
                       class="px-3 py-1.5 bg-blue-700 text-white rounded-lg text-xs hover:bg-blue-800 transition-colors">
                        Download QR
                    </a>
                    <form method="POST" action="{{ route('admin.participants.regenerate-qr', $participant) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition-colors"
                                onclick="return confirm('Generate ulang QR Code? Token lama akan tidak valid.')">
                            Regenerate QR
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.participants.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke daftar</a>
    </div>
</div>
@endsection
