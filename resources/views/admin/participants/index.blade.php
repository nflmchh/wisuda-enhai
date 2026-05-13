@extends('layouts.admin')

@section('title', 'Data Peserta')
@section('header', 'Data Peserta')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5">
    <form method="GET" action="{{ route('admin.participants.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NRP / Email"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="min-w-36">
            <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua</option>
                @foreach(['Wisudawan', 'Orang Tua', 'Tamu Lainnya'] as $cat)
                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-32">
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua</option>
                <option value="hadir" @selected(request('status') === 'hadir')>Hadir</option>
                <option value="belum_hadir" @selected(request('status') === 'belum_hadir')>Belum Hadir</option>
            </select>
        </div>
        <div class="min-w-36">
            <label class="block text-xs font-medium text-gray-600 mb-1">Fakultas</label>
            <select name="faculty" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua</option>
                @foreach($faculties as $fac)
                    <option value="{{ $fac }}" @selected(request('faculty') === $fac)>{{ $fac }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-lg text-sm hover:bg-blue-800 transition-colors">Filter</button>
            <a href="{{ route('admin.participants.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">Reset</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total: <span class="font-semibold text-gray-800">{{ $participants->total() }}</span> peserta</p>
        <a href="{{ route('admin.import') }}" class="text-xs bg-blue-700 text-white px-3 py-1.5 rounded-lg hover:bg-blue-800 transition-colors">+ Import Excel</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Nama</th>
                    <th class="px-5 py-3 text-left">NRP</th>
                    <th class="px-5 py-3 text-left">Kategori</th>
                    <th class="px-5 py-3 text-left">Fakultas</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Jam Hadir</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($participants as $i => $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400">{{ $participants->firstItem() + $i }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $p->name }}</td>
                    <td class="px-5 py-3 text-gray-600 font-mono">{{ $p->nrp }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium
                            {{ $p->category === 'Wisudawan' ? 'bg-indigo-100 text-indigo-700' : ($p->category === 'Orang Tua' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $p->category }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->faculty ?? '-' }}</td>
                    <td class="px-5 py-3">
                        @if($p->attendance_status === 'hadir')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Hadir</span>
                        @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Belum Hadir</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $p->attended_at ? $p->attended_at->format('d/m H:i') : '-' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.participants.show', $p) }}" class="text-blue-600 hover:underline text-xs">Detail</a>
                            <a href="{{ route('admin.participants.edit', $p) }}" class="text-gray-500 hover:underline text-xs">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-gray-400">Tidak ada data peserta</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($participants->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $participants->links() }}
    </div>
    @endif
</div>
@endsection
