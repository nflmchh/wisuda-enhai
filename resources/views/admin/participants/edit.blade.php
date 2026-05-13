@extends('layouts.admin')

@section('title', 'Edit Peserta')
@section('header', 'Edit Peserta')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.participants.update', $participant) }}" class="space-y-4">
            @csrf @method('PUT')

            @foreach([
                ['name', 'Nama Lengkap', 'text', true],
                ['nrp', 'NRP / NIM', 'text', true],
                ['email', 'Email', 'email', false],
                ['faculty', 'Fakultas', 'text', false],
                ['program_study', 'Program Studi', 'text', false],
            ] as [$field, $label, $type, $required])
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
                <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field, $participant->$field) }}"
                       {{ $required ? 'required' : '' }}
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error($field) border-red-400 @enderror">
                @error($field) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            @endforeach

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['Wisudawan', 'Orang Tua', 'Tamu Lainnya'] as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $participant->category) === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-5 py-2 bg-blue-700 text-white rounded-lg text-sm hover:bg-blue-800 transition-colors">Simpan</button>
                <a href="{{ route('admin.participants.show', $participant) }}" class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
