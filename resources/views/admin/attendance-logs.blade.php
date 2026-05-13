@extends('layouts.admin')

@section('title', 'Log Absensi')
@section('header', 'Log Absensi')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5">
    <form method="GET" action="{{ route('admin.attendance-logs') }}" class="flex flex-wrap gap-3 items-end">
        <div class="min-w-32">
            <label class="block text-xs font-medium text-gray-600 mb-1">Gate</label>
            <select name="gate" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Gate</option>
                <option value="1" @selected(request('gate') === '1')>Gate 1</option>
                <option value="2" @selected(request('gate') === '2')>Gate 2</option>
                <option value="3" @selected(request('gate') === '3')>Gate 3</option>
            </select>
        </div>
        <div class="min-w-36">
            <label class="block text-xs font-medium text-gray-600 mb-1">Status Scan</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="success" @selected(request('status') === 'success')>Berhasil</option>
                <option value="duplicate" @selected(request('status') === 'duplicate')>Duplikat</option>
                <option value="invalid" @selected(request('status') === 'invalid')>Invalid</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-lg text-sm hover:bg-blue-800 transition-colors">Filter</button>
            <a href="{{ route('admin.attendance-logs') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">Waktu Scan</th>
                    <th class="px-5 py-3 text-left">Nama Peserta</th>
                    <th class="px-5 py-3 text-left">NRP</th>
                    <th class="px-5 py-3 text-left">Gate</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Pesan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">{{ $log->scanned_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $log->participant->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-600 font-mono">{{ $log->participant->nrp ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-600">Gate {{ $log->scanner_gate }}</td>
                    <td class="px-5 py-3">
                        @if($log->scan_status === 'success')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Berhasil</span>
                        @elseif($log->scan_status === 'duplicate')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Duplikat</span>
                        @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Invalid</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $log->message ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada log absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
