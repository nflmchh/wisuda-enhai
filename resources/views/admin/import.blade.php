@extends('layouts.admin')

@section('title', 'Import Excel')
@section('header', 'Import Excel')

@section('content')
<div class="max-w-2xl">

    {{-- Import Errors --}}
    @if(session('import_errors') && count(session('import_errors')) > 0)
    <div class="mb-5 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <p class="font-medium text-yellow-800 text-sm mb-2">Pesan Import:</p>
        <ul class="text-xs text-yellow-700 space-y-1 list-disc list-inside">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Template Download --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-5 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-blue-800">Unduh Template Excel</p>
            <p class="text-xs text-blue-600 mt-0.5">Gunakan template ini sebagai panduan format data yang benar</p>
        </div>
        <a href="{{ route('admin.import.template') }}"
           class="px-4 py-2 bg-blue-700 text-white rounded-lg text-xs hover:bg-blue-800 transition-colors whitespace-nowrap">
            Download Template
        </a>
    </div>

    {{-- Upload Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Upload File Excel</h3>

        <form method="POST" action="{{ route('admin.import.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors"
                 id="dropzone">
                <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="text-sm text-gray-600 mb-1">Klik atau drag & drop file Excel di sini</p>
                <p class="text-xs text-gray-400">Format: .xlsx atau .xls, Maks. 10MB</p>
                <input type="file" name="file" id="file-input" accept=".xlsx,.xls" required
                       class="mt-3 text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            @error('file') <p class="text-xs text-red-500">{{ $message }}</p> @enderror

            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs font-semibold text-gray-600 mb-2">Kolom yang diperlukan:</p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['name', 'nrp', 'email', 'faculty', 'program_study', 'category'] as $col)
                        <span class="inline-flex items-center px-2 py-1 bg-white border border-gray-200 rounded text-xs font-mono text-gray-700">{{ $col }}</span>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-2">Kolom <strong>category</strong>: Wisudawan / Orang Tua / Tamu Lainnya</p>
            </div>

            <button type="submit" class="w-full py-2.5 bg-blue-700 text-white rounded-lg text-sm font-medium hover:bg-blue-800 transition-colors">
                Import Data
            </button>
        </form>
    </div>
</div>
@endsection
