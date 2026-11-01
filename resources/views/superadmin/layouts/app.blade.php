<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Superadmin') - Monitoring PKL PLTU</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/superadmin/layout.css') }}">

    @stack('styles')
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<div class="app">

    {{-- SIDEBAR (edit menu di partials/sidebar.blade.php) --}}
    @include('superadmin.partials.sidebar')
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main">

        {{-- HEADER (edit di partials/header.blade.php) --}}
        @include('superadmin.partials.header')

        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="app-footer">
            &copy; {{ date('Y') }} PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala.
        </footer>
    </div>

</div>

{{-- MODAL KONFIRMASI (dipakai form yang punya atribut data-confirm) --}}
<div class="modal-backdrop d-none" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
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

<script>
(function () {
    // ---------- Sidebar (mobile) ----------
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var toggle  = document.getElementById('sidebarToggle');
    var closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
        if (toggle) toggle.setAttribute('aria-expanded', 'true');
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
    }

    if (toggle) toggle.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);
    window.addEventListener('resize', function () { if (window.innerWidth > 992) closeSidebar(); });

    // ---------- Modal konfirmasi ----------
    var modal = document.getElementById('confirmModal');
    var msg = document.getElementById('confirmMessage');
    var title = document.getElementById('confirmTitle');
    var okBtn = document.getElementById('confirmOk');
    var cancelBtn = document.getElementById('confirmCancel');
    var pendingForm = null;
    var lastFocus = null;

    function openConfirm(form) {
        pendingForm = form;
        lastFocus = document.activeElement;
        title.textContent = form.dataset.confirmTitle || 'Hapus data?';
        msg.textContent = form.dataset.confirm;
        okBtn.textContent = form.dataset.confirmOk || 'Ya, Hapus';
        modal.classList.remove('d-none');
        cancelBtn.focus();
    }
    function closeConfirm() {
        modal.classList.add('d-none');
        pendingForm = null;
        if (lastFocus) lastFocus.focus();
    }

    document.addEventListener('submit', function (e) {
        var form = e.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed) return;
        e.preventDefault();
        openConfirm(form);
    });

    okBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        var f = pendingForm;
        f.dataset.confirmed = '1';
        modal.classList.add('d-none');
        f.submit();
    });
    cancelBtn.addEventListener('click', closeConfirm);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeConfirm(); });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (!modal.classList.contains('d-none')) closeConfirm();
        else closeSidebar();
    });
})();
</script>

@stack('scripts')
</body>
</html>
