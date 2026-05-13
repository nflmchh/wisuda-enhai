<style>
#mobile-block {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99999;
    overflow: hidden;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 3.5rem 2rem 2rem;
    text-align: center;
}

@media (max-width: 767px) {
    #mobile-block { display: flex; }
}

.mb-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(-45deg, #1a1200, #0d1f1a, #1a0d00, #0a1525);
    background-size: 400% 400%;
    animation: mbGradient 12s ease infinite;
}
@keyframes mbGradient {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.mb-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.25;
    pointer-events: none;
}
.mb-orb-1 {
    width: 250px; height: 250px;
    background: #b45309;
    top: -80px; left: -60px;
    animation: mbF1 9s ease-in-out infinite;
}
.mb-orb-2 {
    width: 180px; height: 180px;
    background: #0f766e;
    bottom: 10%; right: -50px;
    animation: mbF2 11s ease-in-out infinite;
}
.mb-orb-3 {
    width: 140px; height: 140px;
    background: #92400e;
    bottom: 30%; left: -30px;
    animation: mbF3 8s ease-in-out infinite;
}
@keyframes mbF1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(40px,55px)} }
@keyframes mbF2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-35px,-45px)} }
@keyframes mbF3 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(30px,-30px)} }

.mb-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.4rem;
    flex: 1;
    justify-content: center;
}

.mb-mark {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-bottom: 0.5rem;
}
.mb-mark span {
    display: block;
    height: 2px;
    background: rgba(255,255,255,0.25);
    border-radius: 2px;
}
.mb-mark span:nth-child(1) { width: 40px; }
.mb-mark span:nth-child(2) { width: 28px; }
.mb-mark span:nth-child(3) { width: 16px; }

.mb-label {
    color: rgba(255,255,255,0.35);
    font-size: 0.65rem;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    font-family: Georgia, serif;
}

.mb-headline {
    color: #fff;
    font-size: 1.45rem;
    font-weight: 700;
    line-height: 1.45;
    font-family: Georgia, serif;
    letter-spacing: 0.01em;
}

.mb-headline em {
    font-style: italic;
    color: rgba(255,220,120,0.9);
}

.mb-rule {
    width: 36px;
    height: 1px;
    background: rgba(255,255,255,0.2);
}

.mb-desc {
    color: rgba(255,255,255,0.45);
    font-size: 0.82rem;
    line-height: 1.75;
    max-width: 280px;
    font-family: system-ui, sans-serif;
    font-weight: 300;
}

.mb-devices {
    color: rgba(255,255,255,0.3);
    font-size: 0.72rem;
    font-family: system-ui, sans-serif;
    letter-spacing: 0.05em;
}

.mb-footer {
    position: relative;
    z-index: 1;
    color: rgba(255,255,255,0.3);
    font-size: 0.68rem;
    font-family: system-ui, sans-serif;
    padding-bottom: 0.25rem;
}

@keyframes mbColorPulse {
    0%   { color: #fcd34d; text-shadow: 0 0 8px rgba(252,211,77,0.7); }
    25%  { color: #86efac; text-shadow: 0 0 8px rgba(134,239,172,0.7); }
    50%  { color: #f9a8d4; text-shadow: 0 0 8px rgba(249,168,212,0.7); }
    75%  { color: #67e8f9; text-shadow: 0 0 8px rgba(103,232,249,0.7); }
    100% { color: #fcd34d; text-shadow: 0 0 8px rgba(252,211,77,0.7); }
}
.mb-link-rainbow {
    animation: mbColorPulse 4s ease-in-out infinite;
    font-weight: 600;
    text-decoration: none;
}
.mb-link-rainbow:hover {
    animation-play-state: paused;
    color: #fff !important;
}
</style>

<div id="mobile-block">
    <div class="mb-bg"></div>
    <div class="mb-orb mb-orb-1"></div>
    <div class="mb-orb mb-orb-2"></div>
    <div class="mb-orb mb-orb-3"></div>

    <div class="mb-content">
        <div class="mb-mark">
            <span></span><span></span><span></span>
        </div>
        <div class="mb-label">Pemberitahuan</div>
        <div class="mb-headline">
            Aplikasi ini khusus<br>pengguna <em>Desktop,</em><br><em>iPad &amp; Tablet</em>
        </div>
        <div class="mb-rule"></div>
        <div class="mb-desc">
            Untuk pengalaman terbaik, silakan buka kembali melalui perangkat dengan layar yang lebih luas.
        </div>
        <div class="mb-devices">Desktop &nbsp;·&nbsp; iPad &nbsp;·&nbsp; Tablet</div>
    </div>

    <footer class="mb-footer">
        &copy; {{ date('Y') }}
        <a href="https://suddenlycreativestudio.com/" target="_blank" rel="noopener" class="mb-link-rainbow">Suddenly Creative</a>
        &mdash; Virtual Invitation
    </footer>
</div>
