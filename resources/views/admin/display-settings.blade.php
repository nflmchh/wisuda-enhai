@extends('layouts.admin')

@section('title', 'Setting Display TV')
@section('header', 'Setting Display TV')

@section('content')
<form method="POST" action="{{ route('admin.display-settings.update') }}" enctype="multipart/form-data" id="settings-form">
    @csrf @method('PUT')

    {{-- Hidden position inputs (diupdate oleh drag) --}}
    <input type="hidden" name="name_x_position" id="name_x" value="{{ old('name_x_position', $setting->name_x_position) }}">
    <input type="hidden" name="name_y_position" id="name_y" value="{{ old('name_y_position', $setting->name_y_position) }}">
    <input type="hidden" name="nrp_x_position"  id="nrp_x"  value="{{ old('nrp_x_position',  $setting->nrp_x_position) }}">
    <input type="hidden" name="nrp_y_position"  id="nrp_y"  value="{{ old('nrp_y_position',  $setting->nrp_y_position) }}">

    <div class="flex gap-5 items-start">

        {{-- ===== LEFT PANEL: Settings ===== --}}
        <div class="w-72 flex-shrink-0 space-y-4">

            {{-- Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
                <p class="text-xs font-semibold text-blue-800 mb-0.5">Setting berlaku untuk semua gate</p>
                <p class="text-xs text-blue-600">Drag teks di preview untuk atur posisi.</p>
            </div>

            {{-- Background Image --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Background</label>
                @if($setting->background_image)
                    <img src="{{ asset('storage/' . $setting->background_image) }}" id="bg-preview-thumb"
                         class="w-full h-20 object-cover rounded-lg border border-gray-200 mb-2">
                @else
                    <div id="bg-preview-thumb" class="w-full h-20 rounded-lg bg-gradient-to-br from-slate-800 to-blue-900 mb-2 border border-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-xs">Belum ada background</span>
                    </div>
                @endif
                <input type="file" name="background_image" id="bg-file-input" accept="image/*"
                       class="block w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-400 mt-1.5">JPG/PNG/WebP, maks 5MB, 1920×1080px</p>
            </div>

            {{-- Font Sizes --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 space-y-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Ukuran Font</label>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm text-gray-700">Nama</span>
                        <span id="name-size-label" class="text-sm font-bold text-blue-600">{{ $setting->name_font_size }}px</span>
                    </div>
                    <input type="range" name="name_font_size" id="name-font-size"
                           min="20" max="150" value="{{ old('name_font_size', $setting->name_font_size) }}"
                           class="w-full h-2 bg-gray-200 rounded-full appearance-none cursor-pointer accent-blue-600">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm text-gray-700">NRP</span>
                        <span id="nrp-size-label" class="text-sm font-bold text-blue-600">{{ $setting->nrp_font_size }}px</span>
                    </div>
                    <input type="range" name="nrp_font_size" id="nrp-font-size"
                           min="12" max="100" value="{{ old('nrp_font_size', $setting->nrp_font_size) }}"
                           class="w-full h-2 bg-gray-200 rounded-full appearance-none cursor-pointer accent-blue-600">
                </div>
            </div>

            {{-- Font Color --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Warna Teks</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="font_color" id="font-color"
                           value="{{ old('font_color', $setting->font_color) }}"
                           class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5 flex-shrink-0">
                    <div>
                        <p id="color-hex" class="text-sm font-mono font-semibold text-gray-700">{{ $setting->font_color }}</p>
                        <p class="text-xs text-gray-400">Pilih warna yang kontras dengan background</p>
                    </div>
                </div>
            </div>

            {{-- Toggles --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 space-y-3">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Elemen Tambahan</label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="show_category" id="show-category" value="1"
                           {{ old('show_category', $setting->show_category) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <div>
                        <p class="text-sm text-gray-700 font-medium">Tampilkan Kategori</p>
                        <p class="text-xs text-gray-400">Wisudawan / Orang Tua / Tamu</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="show_time" id="show-time" value="1"
                           {{ old('show_time', $setting->show_time) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <div>
                        <p class="text-sm text-gray-700 font-medium">Tampilkan Jam Hadir</p>
                        <p class="text-xs text-gray-400">Muncul di bagian bawah layar</p>
                    </div>
                </label>
            </div>

            {{-- Save Button --}}
            <button type="submit"
                    class="w-full py-3 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                Simpan Setting
            </button>

            {{-- Preview Link --}}
            <a href="{{ route('scanner.show') }}" target="_blank"
               class="block py-2 text-center bg-gray-100 text-gray-600 rounded-lg text-xs hover:bg-gray-200 transition-colors">
                Buka Halaman Scanner
            </a>
        </div>

        {{-- ===== RIGHT PANEL: Preview ===== --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-700">Preview Tampilan TV <span class="text-gray-400 font-normal">(16:9)</span></p>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <span id="hint-name" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>Nama
                        </span>
                        <span id="hint-nrp" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-purple-50 text-purple-600 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-purple-500 inline-block"></span>NRP
                        </span>
                        <span class="text-gray-300">· Drag untuk atur posisi</span>
                    </div>
                </div>

                {{-- Preview Canvas --}}
                <div id="preview-canvas"
                     class="relative w-full overflow-hidden rounded-xl select-none"
                     style="aspect-ratio: 16/9; background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);">

                    {{-- Background image (jika ada) --}}
                    @if($setting->background_image)
                    <img id="preview-bg" src="{{ asset('storage/' . $setting->background_image) }}"
                         class="absolute inset-0 w-full h-full object-cover pointer-events-none"
                         style="z-index:0;">
                    <div class="absolute inset-0 pointer-events-none" style="background:rgba(0,0,0,0.3);z-index:1;"></div>
                    @else
                    <img id="preview-bg" src="" class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden" style="z-index:0;">
                    <div id="preview-bg-overlay" class="absolute inset-0 pointer-events-none hidden" style="background:rgba(0,0,0,0.3);z-index:1;"></div>
                    @endif

                    {{-- Grid helper (muncul saat drag) --}}
                    <div id="grid-overlay" class="absolute inset-0 pointer-events-none opacity-0 transition-opacity duration-200" style="z-index:2;">
                        <svg width="100%" height="100%">
                            {{-- Vertical lines at 25%, 50%, 75% --}}
                            <line x1="25%" y1="0" x2="25%" y2="100%" stroke="rgba(255,255,255,0.1)" stroke-width="1" stroke-dasharray="4,4"/>
                            <line x1="50%" y1="0" x2="50%" y2="100%" stroke="rgba(255,255,255,0.2)" stroke-width="1" stroke-dasharray="4,4"/>
                            <line x1="75%" y1="0" x2="75%" y2="100%" stroke="rgba(255,255,255,0.1)" stroke-width="1" stroke-dasharray="4,4"/>
                            {{-- Horizontal lines at 25%, 50%, 75% --}}
                            <line x1="0" y1="25%" x2="100%" y2="25%" stroke="rgba(255,255,255,0.1)" stroke-width="1" stroke-dasharray="4,4"/>
                            <line x1="0" y1="50%" x2="100%" y2="50%" stroke="rgba(255,255,255,0.2)" stroke-width="1" stroke-dasharray="4,4"/>
                            <line x1="0" y1="75%" x2="100%" y2="75%" stroke="rgba(255,255,255,0.1)" stroke-width="1" stroke-dasharray="4,4"/>
                        </svg>
                    </div>

                    {{-- Draggable: Nama --}}
                    <div id="drag-name"
                         class="absolute cursor-grab active:cursor-grabbing"
                         style="left:{{ $setting->name_x_position }}%;top:{{ $setting->name_y_position }}%;transform:translate(-50%,-50%);z-index:10;">
                        <div class="relative group">
                            {{-- Outline ring saat hover/drag --}}
                            <div class="absolute -inset-2 rounded-lg border-2 border-dashed border-blue-400/0 group-hover:border-blue-400/60 transition-all pointer-events-none" id="name-outline"></div>
                            <p id="preview-name"
                               class="font-black leading-tight whitespace-nowrap drop-shadow-lg"
                               style="font-size:{{ $setting->name_font_size }}px;color:{{ $setting->font_color }};text-shadow:2px 2px 8px rgba(0,0,0,0.8);">
                                NAMA WISUDAWAN
                            </p>
                        </div>
                    </div>

                    {{-- Draggable: NRP --}}
                    <div id="drag-nrp"
                         class="absolute cursor-grab active:cursor-grabbing"
                         style="left:{{ $setting->nrp_x_position }}%;top:{{ $setting->nrp_y_position }}%;transform:translate(-50%,-50%);z-index:10;">
                        <div class="relative group">
                            <div class="absolute -inset-2 rounded-lg border-2 border-dashed border-purple-400/0 group-hover:border-purple-400/60 transition-all pointer-events-none" id="nrp-outline"></div>
                            <p id="preview-nrp"
                               class="font-bold leading-tight whitespace-nowrap drop-shadow-lg font-mono"
                               style="font-size:{{ $setting->nrp_font_size }}px;color:{{ $setting->font_color }};text-shadow:2px 2px 8px rgba(0,0,0,0.8);">
                                2024-00001
                            </p>
                        </div>
                    </div>

                    {{-- Category (jika aktif) --}}
                    <div id="preview-category-wrap"
                         class="absolute pointer-events-none transition-opacity {{ $setting->show_category ? '' : 'opacity-0' }}"
                         style="left:{{ $setting->nrp_x_position }}%;top:calc({{ $setting->nrp_y_position }}% + {{ $setting->nrp_font_size + 8 }}px);transform:translate(-50%,0);z-index:9;">
                        <p id="preview-category"
                           class="whitespace-nowrap drop-shadow"
                           style="font-size:{{ max(16, $setting->nrp_font_size - 12) }}px;color:{{ $setting->font_color }};opacity:0.75;text-shadow:1px 1px 4px rgba(0,0,0,0.8);">
                            Wisudawan
                        </p>
                    </div>

                    {{-- Time (jika aktif) --}}
                    <div id="preview-time-wrap"
                         class="absolute pointer-events-none transition-opacity {{ $setting->show_time ? '' : 'opacity-0' }}"
                         style="left:50%;bottom:5%;transform:translateX(-50%);z-index:9;">
                        <p id="preview-time"
                           class="whitespace-nowrap drop-shadow"
                           style="font-size:22px;color:{{ $setting->font_color }};opacity:0.6;text-shadow:1px 1px 4px rgba(0,0,0,0.8);">
                            Hadir pukul 09:30:00
                        </p>
                    </div>

                    {{-- Koordinat tooltip (muncul saat drag) --}}
                    <div id="coord-tooltip"
                         class="absolute top-3 right-3 bg-black/60 text-white text-xs px-2.5 py-1.5 rounded-lg font-mono opacity-0 transition-opacity pointer-events-none"
                         style="z-index:20;">
                    </div>
                </div>

                {{-- Coordinate display --}}
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-xs text-blue-600 font-medium">Posisi Nama</span>
                        <span class="text-xs font-mono text-blue-700">
                            X: <span id="coord-name-x">{{ $setting->name_x_position }}</span>%
                            &nbsp; Y: <span id="coord-name-y">{{ $setting->name_y_position }}</span>%
                        </span>
                    </div>
                    <div class="bg-purple-50 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-xs text-purple-600 font-medium">Posisi NRP</span>
                        <span class="text-xs font-mono text-purple-700">
                            X: <span id="coord-nrp-x">{{ $setting->nrp_x_position }}</span>%
                            &nbsp; Y: <span id="coord-nrp-y">{{ $setting->nrp_y_position }}</span>%
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<script>
(function () {
    const canvas = document.getElementById('preview-canvas');

    // ── Drag logic ─────────────────────────────────────────────────────────
    function makeDraggable(elId, xInputId, yInputId, coordXId, coordYId) {
        const el = document.getElementById(elId);
        const xInput = document.getElementById(xInputId);
        const yInput = document.getElementById(yInputId);
        const tooltip = document.getElementById('coord-tooltip');
        const grid = document.getElementById('grid-overlay');

        let dragging = false;
        let startMouseX, startMouseY, startLeft, startTop;

        el.addEventListener('mousedown', (e) => {
            e.preventDefault();
            dragging = true;
            el.classList.add('active:cursor-grabbing');
            el.style.cursor = 'grabbing';
            el.style.zIndex = 20;

            const rect = canvas.getBoundingClientRect();
            startMouseX = e.clientX;
            startMouseY = e.clientY;
            startLeft = parseFloat(xInput.value);
            startTop  = parseFloat(yInput.value);

            grid.style.opacity = '1';
            tooltip.style.opacity = '1';
        });

        document.addEventListener('mousemove', (e) => {
            if (!dragging) return;
            const rect = canvas.getBoundingClientRect();

            const dxPx = e.clientX - startMouseX;
            const dyPx = e.clientY - startMouseY;
            const dxPct = (dxPx / rect.width)  * 100;
            const dyPct = (dyPx / rect.height) * 100;

            const newX = Math.round(Math.min(95, Math.max(5, startLeft + dxPct)));
            const newY = Math.round(Math.min(95, Math.max(5, startTop  + dyPct)));

            el.style.left = newX + '%';
            el.style.top  = newY + '%';
            xInput.value  = newX;
            yInput.value  = newY;

            document.getElementById(coordXId).textContent = newX;
            document.getElementById(coordYId).textContent = newY;

            tooltip.textContent = `${elId === 'drag-name' ? 'Nama' : 'NRP'} · X:${newX}% Y:${newY}%`;

            // Sync category position under NRP
            if (elId === 'drag-nrp') updateCategoryPos();
        });

        document.addEventListener('mouseup', () => {
            if (!dragging) return;
            dragging = false;
            el.style.cursor = 'grab';
            el.style.zIndex = 10;
            grid.style.opacity = '0';
            tooltip.style.opacity = '0';
        });

        // Touch support
        el.addEventListener('touchstart', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            dragging = true;
            startMouseX = touch.clientX;
            startMouseY = touch.clientY;
            startLeft = parseFloat(xInput.value);
            startTop  = parseFloat(yInput.value);
            grid.style.opacity = '1';
        }, { passive: false });

        document.addEventListener('touchmove', (e) => {
            if (!dragging) return;
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            const dxPct = ((touch.clientX - startMouseX) / rect.width)  * 100;
            const dyPct = ((touch.clientY - startMouseY) / rect.height) * 100;
            const newX = Math.round(Math.min(95, Math.max(5, startLeft + dxPct)));
            const newY = Math.round(Math.min(95, Math.max(5, startTop  + dyPct)));
            el.style.left = newX + '%';
            el.style.top  = newY + '%';
            xInput.value  = newX;
            yInput.value  = newY;
            document.getElementById(coordXId).textContent = newX;
            document.getElementById(coordYId).textContent = newY;
            if (elId === 'drag-nrp') updateCategoryPos();
        }, { passive: false });

        document.addEventListener('touchend', () => { dragging = false; grid.style.opacity = '0'; });
    }

    makeDraggable('drag-name', 'name_x', 'name_y', 'coord-name-x', 'coord-name-y');
    makeDraggable('drag-nrp',  'nrp_x',  'nrp_y',  'coord-nrp-x',  'coord-nrp-y');

    // ── Font size sliders ───────────────────────────────────────────────────
    const nameFontInput = document.getElementById('name-font-size');
    const nrpFontInput  = document.getElementById('nrp-font-size');
    const previewName   = document.getElementById('preview-name');
    const previewNrp    = document.getElementById('preview-nrp');
    const nameSizeLabel = document.getElementById('name-size-label');
    const nrpSizeLabel  = document.getElementById('nrp-size-label');

    nameFontInput.addEventListener('input', () => {
        const v = nameFontInput.value;
        previewName.style.fontSize = v + 'px';
        nameSizeLabel.textContent = v + 'px';
    });

    nrpFontInput.addEventListener('input', () => {
        const v = nrpFontInput.value;
        previewNrp.style.fontSize = v + 'px';
        nrpSizeLabel.textContent = v + 'px';
        updateCategoryPos();
    });

    // ── Color picker ────────────────────────────────────────────────────────
    const colorInput   = document.getElementById('font-color');
    const colorHex     = document.getElementById('color-hex');
    const previewEls   = [previewName, previewNrp,
                          document.getElementById('preview-category'),
                          document.getElementById('preview-time')];

    colorInput.addEventListener('input', () => {
        const c = colorInput.value;
        colorHex.textContent = c.toUpperCase();
        previewEls.forEach(el => { if (el) el.style.color = c; });
    });

    // ── Show category / time toggles ────────────────────────────────────────
    document.getElementById('show-category').addEventListener('change', (e) => {
        document.getElementById('preview-category-wrap').style.opacity = e.target.checked ? '1' : '0';
    });
    document.getElementById('show-time').addEventListener('change', (e) => {
        document.getElementById('preview-time-wrap').style.opacity = e.target.checked ? '1' : '0';
    });

    // ── Background image live preview ───────────────────────────────────────
    document.getElementById('bg-file-input').addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        const bg = document.getElementById('preview-bg');
        bg.src = url;
        bg.classList.remove('hidden');

        const overlay = document.getElementById('preview-bg-overlay');
        if (overlay) overlay.classList.remove('hidden');

        const thumb = document.getElementById('bg-preview-thumb');
        if (thumb.tagName === 'IMG') {
            thumb.src = url;
        } else {
            const img = document.createElement('img');
            img.id = 'bg-preview-thumb';
            img.className = 'w-full h-20 object-cover rounded-lg border border-gray-200 mb-2';
            img.src = url;
            thumb.replaceWith(img);
        }

        document.getElementById('bg-file-input').before(document.getElementById('bg-preview-thumb'));
    });

    // ── Sync category element under NRP ─────────────────────────────────────
    function updateCategoryPos() {
        const nrpY   = parseFloat(document.getElementById('nrp_y').value);
        const nrpSize = parseInt(document.getElementById('nrp-font-size').value);
        const wrap   = document.getElementById('preview-category-wrap');
        wrap.style.left = document.getElementById('drag-nrp').style.left;
        wrap.style.top  = `calc(${nrpY}% + ${nrpSize + 8}px)`;
    }

})();
</script>
@endsection
