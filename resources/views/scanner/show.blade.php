<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scanner — Absensi Wisuda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            margin: 0; padding: 0;
            width: 100vw; height: 100vh;
            overflow: hidden;
            background: #0f172a;
        }

        #display-wrap {
            position: absolute;
            aspect-ratio: 16 / 9;
            width: max(100vw, calc(100vh * 16 / 9));
            height: max(100vh, calc(100vw * 9 / 16));
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #0f172a;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translate(-50%, calc(-50% + 28px)); }
            to   { opacity: 1; transform: translate(-50%, -50%); }
        }

        .text-el {
            position: absolute;
            transform: translate(-50%, -50%);
            text-align: center;
            white-space: nowrap;
        }

        .text-el.animate-in {
            animation: fadeInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: overlayIn 0.2s ease;
        }

        /* Gate setup screen */
        #gate-setup {
            position: fixed;
            inset: 0;
            z-index: 200;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #gate-setup .setup-box {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 40px 48px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        #gate-setup h1 {
            color: #f1f5f9;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 6px;
        }

        #gate-setup p {
            color: #94a3b8;
            font-size: 0.85rem;
            margin: 0 0 28px;
        }

        #gate-setup input[type="text"] {
            width: 100%;
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 10px;
            color: #f1f5f9;
            font-size: 1rem;
            padding: 12px 16px;
            outline: none;
            box-sizing: border-box;
            margin-bottom: 16px;
            text-align: center;
        }

        #gate-setup input[type="text"]:focus {
            border-color: #3b82f6;
        }

        #gate-setup button {
            width: 100%;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 13px;
            cursor: pointer;
        }

        #gate-setup button:hover { background: #1d4ed8; }

        /* Gate badge bottom-right */
        #gate-badge {
            position: fixed;
            bottom: 1rem;
            right: 1.5rem;
            color: rgba(255,255,255,0.2);
            font-size: 0.65rem;
            letter-spacing: 0.2em;
            font-family: monospace;
            z-index: 100;
            pointer-events: none;
            user-select: none;
        }

        /* Change-gate button (tiny, top-right) */
        #btn-change-gate {
            position: fixed;
            top: 0.75rem;
            right: 1rem;
            z-index: 150;
            background: rgba(255,255,255,0.06);
            border: none;
            color: rgba(255,255,255,0.25);
            font-size: 0.6rem;
            letter-spacing: 0.1em;
            padding: 4px 10px;
            border-radius: 20px;
            cursor: pointer;
            font-family: monospace;
            pointer-events: auto;
        }
        #btn-change-gate:hover { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.5); }
    </style>
</head>
<body>

@include('partials.mobile-block')

{{-- Gate Setup Overlay (shown on first load) --}}
<div id="gate-setup">
    <div class="setup-box">
        <h1>Setup Perangkat Scanner</h1>
        <p>Masukkan nama gate / pintu untuk perangkat ini.<br>Contoh: Gate A, Pintu 1, Utama</p>
        <input id="gate-name-input" type="text" placeholder="Nama gate..." maxlength="40" autocomplete="off">
        <button id="btn-set-gate">Mulai Scanner</button>
    </div>
</div>

{{-- Change gate button (visible after setup) --}}
<button id="btn-change-gate" style="display:none;">GANTI GATE</button>

{{-- Hidden QR input: off-screen, always focused, tabindex=-1 so Tab key skips it --}}
<input id="qr-input" type="text" autocomplete="off" tabindex="-1"
       style="position:fixed;top:-300px;left:-300px;opacity:0;width:1px;height:1px;border:none;outline:none;">

{{-- Main display --}}
<div id="display-wrap"
     @if($setting->background_image)
         style="background-image: url('{{ Storage::url($setting->background_image) }}')"
     @endif
>
    {{-- ── Participant info panel ───────────────────────────────────────── --}}
    <div id="pane-info" style="display:none; position:absolute; inset:0;">

        {{-- Name --}}
        <div id="el-name" class="text-el"
             style="left:{{ $setting->name_x_position }}%; top:{{ $setting->name_y_position }}%;">
            <span id="p-name" style="
                font-size:{{ $setting->name_font_size }}px;
                color:{{ $setting->font_color }};
                font-weight:800;
                text-shadow:0 3px 16px rgba(0,0,0,0.7), 0 1px 4px rgba(0,0,0,0.9);
                line-height:1.1;
                display:block;
            "></span>
        </div>

        {{-- NRP --}}
        <div id="el-nrp" class="text-el"
             style="left:{{ $setting->nrp_x_position }}%; top:{{ $setting->nrp_y_position }}%;">
            <span id="p-nrp" style="
                font-size:{{ $setting->nrp_font_size }}px;
                color:{{ $setting->font_color }};
                font-family:monospace;
                font-weight:600;
                text-shadow:0 2px 10px rgba(0,0,0,0.7);
                display:block;
            "></span>
        </div>

        @if($setting->show_category)
        {{-- Category — offset below NRP by 1.5× NRP font size --}}
        <div id="el-category" class="text-el"
             style="left:{{ $setting->nrp_x_position }}%; top:calc({{ $setting->nrp_y_position }}% + {{ $setting->nrp_font_size * 1.5 }}px);">
            <span id="p-category" style="
                font-size:{{ max(16, (int)round($setting->nrp_font_size * 0.6)) }}px;
                color:{{ $setting->font_color }};
                opacity:0.8;
                text-shadow:0 1px 6px rgba(0,0,0,0.7);
                display:block;
            "></span>
        </div>
        @endif

        @if($setting->show_time)
        @php
            $timeOffset = $setting->nrp_font_size * 1.5
                + ($setting->show_category ? max(16, (int)round($setting->nrp_font_size * 0.6)) * 1.6 : 0);
        @endphp
        <div id="el-time" class="text-el"
             style="left:{{ $setting->nrp_x_position }}%; top:calc({{ $setting->nrp_y_position }}% + {{ $timeOffset }}px);">
            <span id="p-time" style="
                font-size:{{ max(14, (int)round($setting->nrp_font_size * 0.5)) }}px;
                color:{{ $setting->font_color }};
                opacity:0.6;
                text-shadow:0 1px 4px rgba(0,0,0,0.7);
                display:block;
            "></span>
        </div>
        @endif
    </div>

    {{-- ── Duplicate overlay ────────────────────────────────────────────── --}}
    <div id="pane-duplicate" class="overlay" style="display:none; background:rgba(120,53,15,0.88);">
        <div style="text-align:center; color:#fff; padding:2rem; max-width:640px;">
            <div style="font-size:4.5rem; line-height:1; margin-bottom:1rem;">⚠️</div>
            <div style="font-size:2.25rem; font-weight:800; letter-spacing:0.05em; margin-bottom:1rem;">
                SUDAH PERNAH ABSEN
            </div>
            <div id="dup-name" style="font-size:1.75rem; font-weight:700; margin-bottom:0.35rem;"></div>
            <div id="dup-nrp"  style="font-size:1.2rem; font-family:monospace; opacity:0.8;"></div>
            <div id="dup-time" style="font-size:0.9rem; opacity:0.6; margin-top:0.5rem;"></div>
        </div>
    </div>

    {{-- ── Invalid overlay ──────────────────────────────────────────────── --}}
    <div id="pane-invalid" class="overlay" style="display:none; background:rgba(127,29,29,0.9);">
        <div style="text-align:center; color:#fff; padding:2rem;">
            <div style="font-size:4.5rem; line-height:1; margin-bottom:1rem; font-weight:700;">✕</div>
            <div style="font-size:2.25rem; font-weight:800; letter-spacing:0.05em; margin-bottom:0.5rem;">
                QR TIDAK VALID
            </div>
            <div style="font-size:0.9rem; opacity:0.6;">Token tidak ditemukan dalam sistem</div>
        </div>
    </div>
</div>

{{-- Gate badge bottom-right --}}
<div id="gate-badge"></div>

<script>
const SCAN_URL   = '{{ route('scanner.scan') }}';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
const input      = document.getElementById('qr-input');

let currentGate  = localStorage.getItem('scannerGate') || '';
let resetTimer   = null;

// ── Scan queue — ensures requests are processed one-at-a-time ───────────────
const scanQueue  = [];
let scanning     = false;

async function processQueue() {
    if (scanning || scanQueue.length === 0) return;
    scanning = true;
    const token = scanQueue.shift();
    try {
        await doScan(token);
    } catch (err) {
        console.error('Scan error:', err);
    }
    scanning = false;
    processQueue();
}

async function doScan(token) {
    const res  = await fetch(SCAN_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ token, gate: currentGate }),
    });
    const data = await res.json();

    hideAll();

    if (data.status === 'success') {
        document.getElementById('p-name').textContent = data.name;
        document.getElementById('p-nrp').textContent  = data.nrp;
        @if($setting->show_category)
        const catEl = document.getElementById('p-category');
        if (catEl) catEl.textContent = data.category ?? '';
        @endif
        @if($setting->show_time)
        const timeEl = document.getElementById('p-time');
        if (timeEl) timeEl.textContent = 'Jam: ' + data.attended_at;
        @endif

        document.getElementById('pane-info').style.display = 'block';
        triggerAnim();
        resetTimer = setTimeout(hideAll, 30000);

    } else if (data.status === 'duplicate') {
        document.getElementById('dup-name').textContent = data.name;
        document.getElementById('dup-nrp').textContent  = data.nrp;
        document.getElementById('dup-time').textContent = 'Hadir pada: ' + data.attended_at;
        document.getElementById('pane-duplicate').style.display = 'flex';
        resetTimer = setTimeout(hideAll, 5000);

    } else {
        document.getElementById('pane-invalid').style.display = 'flex';
        resetTimer = setTimeout(hideAll, 3000);
    }
}

// ── Helpers ──────────────────────────────────────────────────────────────────
function hideAll() {
    if (resetTimer) { clearTimeout(resetTimer); resetTimer = null; }
    document.getElementById('pane-info').style.display      = 'none';
    document.getElementById('pane-duplicate').style.display = 'none';
    document.getElementById('pane-invalid').style.display   = 'none';
}

function triggerAnim() {
    document.querySelectorAll('#pane-info .text-el').forEach(el => {
        el.classList.remove('animate-in');
        void el.offsetWidth;
        el.classList.add('animate-in');
    });
}

// ── QR input listener ────────────────────────────────────────────────────────
input.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    const token = input.value.trim();
    input.value = '';
    if (!token) return;
    scanQueue.push(token);
    processQueue();
});

// ── Focus management ──────────────────────────────────────────────────────────
function refocusInput() {
    if (document.getElementById('gate-setup').style.display === 'none') {
        input.focus();
    }
}
document.addEventListener('click',   refocusInput);
document.addEventListener('keydown', () => { if (document.activeElement !== input) refocusInput(); });

// ── Gate setup ────────────────────────────────────────────────────────────────
const setupEl       = document.getElementById('gate-setup');
const gateInput     = document.getElementById('gate-name-input');
const btnSet        = document.getElementById('btn-set-gate');
const btnChange     = document.getElementById('btn-change-gate');
const gateBadge     = document.getElementById('gate-badge');

function activateScanner(gateName) {
    currentGate = gateName.trim() || 'Utama';
    localStorage.setItem('scannerGate', currentGate);
    gateBadge.textContent = currentGate.toUpperCase();
    setupEl.style.display = 'none';
    btnChange.style.display = 'block';
    input.focus();
}

function showSetup() {
    hideAll();
    setupEl.style.display = 'flex';
    gateInput.value = currentGate;
    gateInput.focus();
    gateInput.select();
    btnChange.style.display = 'none';
}

btnSet.addEventListener('click', () => {
    if (!gateInput.value.trim()) { gateInput.focus(); return; }
    activateScanner(gateInput.value);
});

gateInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { btnSet.click(); }
});

btnChange.addEventListener('click', showSetup);

// Auto-activate if gate was already saved
if (currentGate) {
    activateScanner(currentGate);
} else {
    gateInput.focus();
}
</script>
</body>
</html>
