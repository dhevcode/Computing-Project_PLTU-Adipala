<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Pembimbing') - Sistem Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS LAYOUT SUPERADMIN / ADMIN (Agar Identik) -->
    <link rel="stylesheet" href="{{ asset('css/superadmin/layout.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* Animasi */
        .animate-fade-up { opacity: 0; transform: translateY(20px); animation: fadeUp 0.5s ease forwards; }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

        /* Custom Dropdown Filter */
        .custom-select-wrapper { position: relative; width: 100%; user-select: none; }
        .custom-select-trigger {
            width: 100%; height: 38px; padding: 0 12px; font-size: 13px; font-weight: 500; color: var(--text);
            background: #fff; border: 1.5px solid var(--border); border-radius: 8px; cursor: pointer;
            display: flex; justify-content: space-between; align-items: center; transition: 0.2s;
        }
        .custom-select-trigger i { font-size: 11px; color: var(--muted); transition: transform 0.3s; }
        .custom-select-wrapper.open .custom-select-trigger { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
        .custom-select-wrapper.open .custom-select-trigger i { transform: rotate(180deg); color: var(--primary); }
        .custom-select-options {
            position: absolute; top: calc(100% + 4px); left: 0; width: 100%; background: #fff;
            border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 10px 25px rgba(26,43,60,0.1);
            z-index: 99; padding: 6px; opacity: 0; visibility: hidden; transform: translateY(-10px);
            transition: all 0.2s ease; max-height: 220px; overflow-y: auto;
        }
        .custom-select-wrapper.open .custom-select-options { opacity: 1; visibility: visible; transform: translateY(0); }
        .custom-option { padding: 8px 12px; font-size: 13px; border-radius: 6px; cursor: pointer; margin-bottom: 2px; transition: 0.2s; }
        .custom-option:hover, .custom-option.selected { background: var(--primary-soft); color: var(--primary-dark); font-weight: 600; }

        .input-box, .flatpickr-input {
            width: 100%; height: 38px; padding: 0 12px; border-radius: 8px; border: 1.5px solid var(--border);
            font-size: 13px; outline: none; transition: 0.2s; background: #fff; cursor: pointer; font-family: inherit;
        }
        .input-box:focus, .flatpickr-input:focus { border-color: var(--primary); }
    </style>
    @stack('styles')
</head>
<body>

<div class="app">
    <!-- SIDEBAR PEMBIMBING -->
    @include('pembimbing.sidebar')
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main">
        <!-- HEADER TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle" id="sidebarToggle" aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <h1>@yield('page_title', 'Dashboard Pembimbing')</h1>
                    <p>@yield('page_subtitle', 'Sistem Monitoring Mahasiswa PKL')</p>
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('pembimbing.profil') }}" class="user-chip" title="Lihat Profil Saya">
                    <div class="avatar-circle">{{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'P', 0, 1)) }}</div>
                    <div class="user-chip-text">
                        <strong>{{ auth()->user()->nama_lengkap ?? 'Pembimbing' }}</strong>
                        <span>Pembimbing Lapangan</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- KONTEN UTAMA -->
        <main class="content">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="app-footer">
            &copy; {{ date('Y') }} PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala.
        </footer>
    </div>
</div>

<!-- ================= MODAL KONFIRMASI ================= -->
<div class="modal-backdrop d-none" id="confirmModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h3 id="confirmTitle">Konfirmasi Aksi</h3>
        <p id="confirmMessage"></p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" id="confirmCancel">Batal</button>
            <button type="button" class="btn-modal-danger" id="confirmOk">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================= 1. FIX SIDEBAR MOBILE ================= */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    if(toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if(closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if(overlay) overlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth > 992) {
            closeSidebar();
        }
    });

    /* ================= 2. CUSTOM DROPDOWN ================= */
    document.querySelectorAll('select.custom-filter-select').forEach(select => {
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';

        const activeOption = select.options[select.selectedIndex];
        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.innerHTML = `<span>${activeOption ? activeOption.text : 'Pilih...'}</span> <i class="fa-solid fa-chevron-down"></i>`;

        const optionsPanel = document.createElement('div');
        optionsPanel.className = 'custom-select-options';

        Array.from(select.options).forEach(option => {
            const optDiv = document.createElement('div');
            optDiv.className = 'custom-option';
            optDiv.textContent = option.text;
            if (option.selected) optDiv.classList.add('selected');

            optDiv.addEventListener('click', (e) => {
                e.stopPropagation();
                select.value = option.value;
                trigger.querySelector('span').textContent = option.text;
                wrapper.classList.remove('open');

                optionsPanel.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                optDiv.classList.add('selected');
            });
            optionsPanel.appendChild(optDiv);
        });

        wrapper.appendChild(trigger);
        wrapper.appendChild(optionsPanel);
        select.parentNode.insertBefore(wrapper, select.nextSibling);

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                if(w !== wrapper) w.classList.remove('open');
            });
            wrapper.classList.toggle('open');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
    });

    /* ================= 3. FLATPICKR ================= */
    flatpickr(".flatpickr-date", { dateFormat: "Y-m-d", locale: "id", disableMobile: "true" });
});
</script>
@stack('scripts')
</body>
</html>