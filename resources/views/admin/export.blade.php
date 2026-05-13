@extends('layouts.admin')

@section('title', 'Export Data')
@section('header', 'Export Data')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-700 mb-5">Filter Export</h3>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                    <select id="filter-status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="belum_hadir">Belum Hadir</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="filter-category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        <option value="Wisudawan">Wisudawan</option>
                        <option value="Orang Tua">Orang Tua</option>
                        <option value="Tamu Lainnya">Tamu Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                    <select id="filter-faculty" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Fakultas</option>
                        @foreach($faculties as $fac)
                            <option value="{{ $fac }}">{{ $fac }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button onclick="exportData('excel')"
                        class="flex-1 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </button>
                <button onclick="exportData('pdf')"
                        class="flex-1 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function exportData(type) {
    const status = document.getElementById('filter-status').value;
    const category = document.getElementById('filter-category').value;
    const faculty = document.getElementById('filter-faculty').value;

    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (category) params.append('category', category);
    if (faculty) params.append('faculty', faculty);

    const routes = {
        excel: '{{ route('admin.export.excel') }}',
        pdf: '{{ route('admin.export.pdf') }}'
    };

    window.location.href = routes[type] + (params.toString() ? '?' + params.toString() : '');
}
</script>
@endsection
