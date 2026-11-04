<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Peserta') - Sistem Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS LAYOUT SUPERADMIN (Agar Identik dengan Admin) -->
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
        
        /* Badge Notifikasi pada Topbar */
        .header-action-btn {
            position: relative; border: none; background: transparent; cursor: pointer;
            font-size: 18px; color: var(--muted); display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 50%; transition: 0.2s;
        }
        .header-action-btn:hover { background: var(--border); color: var(--primary-dark); }
        .notif-badge-pill {
            position: absolute; top: 0px; right: 2px; background: #EF4444; color: #ffffff;
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 9999px; border: 2px solid #fff;
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="app">
    <!-- MENGGUNAKAN INCLUDE SIDEBAR SEPERTI ADMIN -->
    @include('peserta.sidebar')
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main">
        <!-- HEADER TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle" id="sidebarToggle" aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <h1>@yield('page_title', 'Dashboard Peserta')</h1>
                    <p>@yield('page_subtitle', 'Sistem Monitoring Magang PLTU Adipala')</p>
                </div>
            </div>
            <div class="topbar-right" style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('peserta.notifikasi') }}" class="header-action-btn" title="Lihat Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    @php $unreadCount = Auth::user()->unreadNotificationsCount() ?? 0; @endphp
                    @if($unreadCount > 0)
                        <span class="notif-badge-pill">{{ $unreadCount }}</span>
                    @endif
                </a>
                
                <a href="{{ route('peserta.profil') }}" class="user-chip" title="Lihat Profil Saya">
                    <div class="avatar-circle">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'P', 0, 1)) }}</div>
                    <div class="user-chip-text">
                        <strong>{{ Str::limit(Auth::user()->nama_lengkap ?? 'Peserta', 16) }}</strong>
                        <span>Peserta Magang</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- KONTEN UTAMA -->
        <main class="content">
            <!-- Alert Global -->
            @if(session('success'))
                <div class="alert alert-success animate-fade-up" style="margin-bottom: 24px;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error animate-fade-up" style="margin-bottom: 24px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-error animate-fade-up" style="margin-bottom: 24px;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>Terdapat kesalahan:</strong>
                        <ul style="margin: 4px 0 0 16px; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="app-footer">
            &copy; {{ date('Y') }} PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala.
        </footer>
    </div>
</div>

<!-- ================= MODAL KONFIRMASI DELETE ================= -->
<div class="modal-backdrop d-none" id="confirmModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fa-solid fa-trash-can"></i></div>
        <h3 id="confirmTitle">Hapus data?</h3>
        <p id="confirmMessage"></p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" id="confirmCancel">Batal</button>
            <button type="button" class="btn-modal-danger" id="confirmOk">Ya, Hapus</button>
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
                
                select.dispatchEvent(new Event('change'));
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
    if (typeof flatpickr !== 'undefined') {
        flatpickr(".flatpickr-date", { dateFormat: "Y-m-d", locale: "id", disableMobile: "true" });
    }

    /* ================= 4. MODAL KONFIRMASI DELETE ================= */
    const modal = document.getElementById('confirmModal');
    const msg = document.getElementById('confirmMessage');
    const title = document.getElementById('confirmTitle');
    const okBtn = document.getElementById('confirmOk');
    const cancelBtn = document.getElementById('confirmCancel');
    let pendingForm = null;

    document.addEventListener('submit', function (e) {
        var form = e.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed) return;
        e.preventDefault();
        pendingForm = form;
        if(title) title.textContent = form.dataset.confirmTitle || 'Konfirmasi Tindakan';
        if(msg) msg.textContent = form.dataset.confirm;
        if(modal) modal.classList.remove('d-none');
    });

    if(okBtn) okBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        pendingForm.dataset.confirmed = '1';
        if(modal) modal.classList.add('d-none');
        pendingForm.submit();
    });

    if(cancelBtn) cancelBtn.addEventListener('click', () => modal.classList.add('d-none'));
    if(modal) modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('d-none'); });
});
</script>
@stack('scripts')
@include('components.chatbot-widget')
</body>
</html>