@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Peserta</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($total) }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-green-600 uppercase tracking-wider">Sudah Hadir</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($hadir) }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-red-500 uppercase tracking-wider">Belum Hadir</p>
        <p class="text-3xl font-bold text-red-500 mt-1">{{ number_format($belumHadir) }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-blue-600 uppercase tracking-wider">Persentase Hadir</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $persentase }}%</p>
    </div>
</div>

{{-- Progress Bar --}}
<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6">
    <div class="flex justify-between items-center mb-2">
        <span class="text-sm font-medium text-gray-700">Progress Kehadiran</span>
        <span class="text-sm font-semibold text-blue-600">{{ $hadir }}/{{ $total }} peserta</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-3">
        <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $persentase }}%"></div>
    </div>
</div>

{{-- Per Category --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    @foreach([['Wisudawan', $wisudawanHadir, 'bg-indigo-50 border-indigo-200 text-indigo-700'], ['Orang Tua', $orangtuaHadir, 'bg-purple-50 border-purple-200 text-purple-700'], ['Tamu Lainnya', $tamuHadir, 'bg-amber-50 border-amber-200 text-amber-700']] as [$label, $count, $style])
    <div class="rounded-xl p-4 border {{ $style }}">
        <p class="text-xs font-medium uppercase tracking-wider opacity-75">{{ $label }} Hadir</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($count) }}</p>
    </div>
    @endforeach
</div>

{{-- Recent Scans --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">Scan Terbaru</h3>
        <a href="{{ route('admin.attendance-logs') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">Waktu</th>
                    <th class="px-5 py-3 text-left">Nama</th>
                    <th class="px-5 py-3 text-left">NRP</th>
                    <th class="px-5 py-3 text-left">Gate</th>
                    <th class="px-5 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentScans as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $log->scanned_at->format('H:i:s') }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $log->participant->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $log->participant->nrp ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">Gate {{ $log->scanner_gate }}</td>
                    <td class="px-5 py-3">
                        @if($log->scan_status === 'success')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Hadir</span>
                        @elseif($log->scan_status === 'duplicate')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Duplikat</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Invalid</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada data scan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
