@extends('layouts.admin')

@section('title', 'Email Blast')
@section('header', 'Email Blast')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Total Email', $stats['total'], 'text-gray-700'],
        ['Terkirim', $stats['terkirim'], 'text-green-600'],
        ['Belum Dikirim', $stats['belum'], 'text-blue-600'],
        ['Gagal', $stats['gagal'], 'text-red-500'],
    ] as [$label, $count, $color])
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</p>
        <p class="text-3xl font-bold {{ $color }} mt-1">{{ $count }}</p>
    </div>
    @endforeach
</div>

{{-- Send Actions --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
    <h3 class="font-semibold text-gray-700 mb-4">Kirim Email Undangan</h3>
    <div class="flex flex-wrap gap-3">
        <form method="POST" action="{{ route('admin.email-blast.send') }}">
            @csrf
            <input type="hidden" name="mode" value="belum">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-lg text-sm hover:bg-blue-800 transition-colors"
                    onclick="return confirm('Kirim email ke semua peserta yang belum dikirim? ({{ $stats['belum'] }} peserta)')">
                Kirim ke Belum Dikirim ({{ $stats['belum'] }})
            </button>
        </form>
        <form method="POST" action="{{ route('admin.email-blast.send') }}">
            @csrf
            <input type="hidden" name="mode" value="semua">
            <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg text-sm hover:bg-gray-800 transition-colors"
                    onclick="return confirm('Kirim email ke SEMUA peserta ({{ $stats['total'] }} peserta)?')">
                Kirim Ulang Semua ({{ $stats['total'] }})
            </button>
        </form>
        @if($stats['gagal'] > 0)
        <form method="POST" action="{{ route('admin.email-blast.send') }}">
            @csrf
            <input type="hidden" name="mode" value="gagal">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition-colors"
                    onclick="return confirm('Kirim ulang yang gagal? ({{ $stats['gagal'] }} peserta)')">
                Kirim Ulang Gagal ({{ $stats['gagal'] }})
            </button>
        </form>
        @endif
    </div>
    <p class="text-xs text-gray-400 mt-3">Catatan: Proses pengiriman email membutuhkan waktu. Pastikan konfigurasi SMTP sudah benar.</p>
</div>

{{-- Log Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">Log Pengiriman Email</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">Waktu</th>
                    <th class="px-5 py-3 text-left">Peserta</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Keterangan</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">{{ $log->created_at->format('d/m H:i') }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $log->participant->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $log->email }}</td>
                    <td class="px-5 py-3">
                        @if($log->status === 'terkirim')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Terkirim</span>
                        @elseif($log->status === 'gagal')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Gagal</span>
                        @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Belum</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs max-w-xs truncate">{{ $log->error_message ?? '-' }}</td>
                    <td class="px-5 py-3">
                        @if($log->participant && $log->participant->email)
                        <form method="POST" action="{{ route('admin.email-blast.send-one', $log->participant) }}">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:underline">Kirim Ulang</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada log email</td>
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
