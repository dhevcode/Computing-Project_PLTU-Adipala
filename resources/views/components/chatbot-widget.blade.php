<!-- ================= CHATBOT ASISTEN VIRTUAL WIDGET ================= -->
<div id="pltu-chatbot-container">

    <!-- FLOATING MOVABLE BUBBLE TRIGGER -->
    <div id="chatbot-trigger" class="chatbot-bubble" title="Klik untuk membuka Asisten Virtual PLTU">
        <div class="bubble-inner">
            <div class="bubble-icon">
                <i class="fa-solid fa-robot"></i>
                <span class="online-indicator"></span>
            </div>
            <div class="bubble-label">
                <span>Tanya Kami</span>
            </div>
        </div>
        <div class="drag-handle" title="Tahan dan geser ke atas/bawah">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </div>
    </div>

    <!-- CHATBOT WINDOW DIALOG -->
    <div id="chatbot-window" class="chatbot-window" style="display: none;">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    <i class="fa-solid fa-robot"></i>
                    <span class="online-dot"></span>
                </div>
                <div>
                    <h4>Asisten Virtual PLTU</h4>
                    <p>Layanan Informasi & FAQ Magang</p>
                </div>
            </div>
            <div class="chat-header-actions">
                <button type="button" id="chatbot-restart-btn" class="chat-tool-btn" title="Mulai Ulang Percakapan">
                    <i class="fa-solid fa-rotate-left"></i>
                </button>
                <button type="button" id="chatbot-close-btn" class="chat-tool-btn" title="Tutup Chat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Role Banner -->
        <div class="chat-role-banner" id="chat-role-banner">
            <i class="fa-solid fa-shield-halved"></i>
            <span id="chat-role-text">Mode Akses: Publik / Tamu</span>
        </div>

        <!-- Chat Messages Stream -->
        <div class="chat-body" id="chat-messages-container">
            <!-- Messages and FAQ buttons will be rendered dynamically here -->
        </div>

        <!-- Footer Notice (No Free Text Typing) -->
        <div class="chat-footer-note">
            <i class="fa-solid fa-hand-pointer"></i>
            <span>Pilih salah satu tombol pertanyaan di atas untuk melihat jawaban.</span>
        </div>
    </div>

</div>

<!-- ================= CSS CHATBOT WIDGET ================= -->
<style>
    /* ================= CONTAINER & FLOATING BUBBLE ================= */
    #pltu-chatbot-container {
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        position: relative;
        z-index: 999999;
    }

    .chatbot-bubble {
        position: fixed;
        right: 24px;
        top: 65%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        padding: 12px 16px 12px 18px;
        border-radius: 999px;
        box-shadow: 0 10px 25px rgba(0, 162, 185, 0.35), 0 4px 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        user-select: none;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        touch-action: none;
    }

    .chatbot-bubble:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 14px 30px rgba(0, 162, 185, 0.45);
    }

    .chatbot-bubble.dragging {
        transition: none !important;
        opacity: 0.95;
        cursor: grabbing !important;
        transform: translateY(-50%) scale(1.08);
    }

    .bubble-inner {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .bubble-icon {
        position: relative;
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .bubble-icon .online-indicator {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background: var(--success-text);
        border: 2px solid var(--primary-dark);
        border-radius: 50%;
        animation: pulseIndicator 2s infinite;
    }

    @keyframes pulseIndicator {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.25); opacity: 0.8; }
    }

    .bubble-label {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .drag-handle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
        padding-left: 4px;
        cursor: grab;
    }

    .drag-handle:hover { color: #ffffff; }

    /* ================= CHAT WINDOW ================= */
    .chatbot-window {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 380px;
        max-width: calc(100vw - 48px);
        height: 600px;
        max-height: calc(100vh - 48px);
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(26, 43, 60, 0.15), 0 0 0 1px rgba(226, 232, 240, 0.8);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: chatWindowOpen 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 9999999;
    }

    @keyframes chatWindowOpen {
        from { opacity: 0; transform: scale(0.9) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Header */
    .chat-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-avatar {
        position: relative;
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .chat-avatar .online-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 10px;
        height: 10px;
        background: var(--success-text);
        border: 2px solid var(--primary-dark);
        border-radius: 50%;
    }

    .chat-header-info h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.2;
    }
    .chat-header-info p {
        margin: 4px 0 0 0;
        font-size: 11.5px;
        color: rgba(255, 255, 255, 0.85);
    }

    .chat-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-tool-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: #ffffff;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.2s, transform 0.2s;
    }
    .chat-tool-btn:hover { background: rgba(255, 255, 255, 0.3); transform: scale(1.05); }

    /* Role Banner */
    .chat-role-banner {
        background: var(--success-bg);
        border-bottom: 1px solid #BBF7D0;
        color: var(--success-text);
        font-size: 11.5px;
        font-weight: 700;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .chat-role-banner.guest-banner {
        background: var(--page);
        border-bottom: 1px solid var(--border);
        color: var(--muted);
    }

    /* Chat Messages Body */
    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 16px;
        scroll-behavior: smooth;
    }

    .chat-body::-webkit-scrollbar { width: 6px; }
    .chat-body::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }

    /* Message Bubbles */
    .chat-msg {
        display: flex;
        gap: 12px;
        max-width: 90%;
        animation: msgFadeIn 0.25s ease-out;
    }

    @keyframes msgFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chat-msg.bot { align-self: flex-start; }
    .chat-msg.user { align-self: flex-end; flex-direction: row-reverse; }

    .msg-bot-avatar {
        width: 32px;
        height: 32px;
        background: var(--primary);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .msg-content {
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 13px;
        line-height: 1.6;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .chat-msg.bot .msg-content {
        background: #ffffff;
        color: var(--text);
        border-top-left-radius: 4px;
        border: 1px solid var(--border);
    }

    .chat-msg.user .msg-content {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        border-top-right-radius: 4px;
    }

    .msg-time {
        display: block;
        font-size: 10px;
        color: var(--muted);
        margin-top: 6px;
        text-align: right;
    }
    .chat-msg.user .msg-time { color: rgba(255, 255, 255, 0.8); }

    /* Typing Indicator */
    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 12px 16px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 14px;
        width: fit-content;
    }
    .typing-dot {
        width: 6px;
        height: 6px;
        background: var(--primary);
        border-radius: 50%;
        animation: typingAnimation 1.4s infinite ease-in-out;
    }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typingAnimation {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1.1); opacity: 1; }
    }

    /* FAQ Quick Reply Buttons */
    .faq-options-block {
        margin-top: 4px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        animation: msgFadeIn 0.25s ease-out;
    }
    .faq-options-title {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .faq-btn {
        background: #ffffff;
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 12.5px;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: all 0.2s ease;
        line-height: 1.4;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        font-family: inherit;
    }
    .faq-btn:hover {
        background: var(--primary-soft);
        border-color: var(--primary);
        color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 162, 185, 0.15);
    }

    .faq-badge {
        background: var(--page);
        color: var(--muted);
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        flex-shrink: 0;
        margin-top: 1px;
        transition: all 0.2s;
    }
    .faq-btn:hover .faq-badge { background: var(--primary); color: #ffffff; }

    .faq-text { flex: 1; }
    .faq-arrow { color: var(--muted); font-size: 11px; margin-top: 3px; transition: transform 0.2s; }
    .faq-btn:hover .faq-arrow { color: var(--primary-dark); transform: translateX(3px); }

    /* Action Chips after Answer */
    .post-answer-nav {
        margin-top: 6px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .nav-chip {
        background: var(--page);
        color: var(--text);
        border: 1px solid var(--border);
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        font-family: inherit;
    }
    .nav-chip:hover {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
    }

    /* Footer Note */
    .chat-footer-note {
        padding: 12px 20px;
        background: var(--page);
        border-top: 1px solid var(--border);
        font-size: 11.5px;
        font-weight: 500;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 10px;
        line-height: 1.4;
    }
    .chat-footer-note i {
        color: var(--primary);
        font-size: 14px;
        flex-shrink: 0;
    }

    /* =========================================
       RESPONSIVE MOBILE (POP-UP STYLE)
       ========================================= */
    @media (max-width: 760px) {
        .chatbot-window {
            right: 16px;
            bottom: 85px; /* Mengambang di atas bubble trigger */
            width: calc(100vw - 32px);
            max-width: 100%;
            height: calc(100vh - 120px); /* Menyisakan ruang di atas agar tidak bertabrakan dengan URL bar HP */
            max-height: 550px;
            border-radius: 16px;
        }

        .chat-header {
            border-radius: 16px 16px 0 0;
        }

        .chatbot-bubble {
            right: 16px;
            padding: 10px 14px;
            /* Memaksa posisi ke pojok kanan bawah, mengabaikan JS drag di HP */
            top: auto !important;
            bottom: 20px !important;
            transform: none !important;
        }

        /* Sembunyikan label dan drag-handle di HP agar lebih compact */
        .bubble-label { display: none; }
        .drag-handle { display: none; }
    }
</style>

<!-- ================= JAVASCRIPT CHATBOT ================= -->
<script>
(function() {
    // State
    let faqQuestions = [];
    let currentRole = 'guest';
    let currentUserName = null;
    let isDragging = false;
    let hasDragged = false;
    let startY = 0;
    let initialTop = 0;

    const trigger = document.getElementById('chatbot-trigger');
    const windowEl = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close-btn');
    const restartBtn = document.getElementById('chatbot-restart-btn');
    const messagesContainer = document.getElementById('chat-messages-container');
    const roleBanner = document.getElementById('chat-role-banner');
    const roleText = document.getElementById('chat-role-text');

    // Restore saved vertical position (Desktop Only)
    const savedTop = localStorage.getItem('pltu_chatbot_top');
    if (savedTop && trigger && window.innerWidth > 760) {
        trigger.style.top = savedTop;
    }

    // ================= 1. DRAG / MOVE LOGIC =================
    if (trigger) {
        const startDrag = (clientY) => {
            if(window.innerWidth <= 760) return; // Nonaktifkan drag di HP
            isDragging = true;
            hasDragged = false;
            startY = clientY;
            const rect = trigger.getBoundingClientRect();
            initialTop = rect.top + (rect.height / 2);
            trigger.classList.add('dragging');
        };

        const onDrag = (clientY) => {
            if (!isDragging) return;
            const deltaY = clientY - startY;
            if (Math.abs(deltaY) > 5) {
                hasDragged = true;
            }

            let newTop = initialTop + deltaY;
            const minTop = 60;
            const maxTop = window.innerHeight - 60;
            if (newTop < minTop) newTop = minTop;
            if (newTop > maxTop) newTop = maxTop;

            trigger.style.top = newTop + 'px';
        };

        const endDrag = () => {
            if (!isDragging) return;
            isDragging = false;
            trigger.classList.remove('dragging');
            localStorage.setItem('pltu_chatbot_top', trigger.style.top);
        };

        trigger.addEventListener('mousedown', (e) => { startDrag(e.clientY); });
        window.addEventListener('mousemove', (e) => { onDrag(e.clientY); });
        window.addEventListener('mouseup', () => { endDrag(); });

        trigger.addEventListener('touchstart', (e) => {
            if (e.touches.length > 0) startDrag(e.touches[0].clientY);
        }, { passive: true });
        window.addEventListener('touchmove', (e) => {
            if (e.touches.length > 0) onDrag(e.touches[0].clientY);
        }, { passive: true });
        window.addEventListener('touchend', () => { endDrag(); });

        trigger.addEventListener('click', (e) => {
            if (hasDragged) {
                e.preventDefault();
                hasDragged = false;
                return;
            }
            toggleChatWindow();
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', () => windowEl.style.display = 'none');
    if (restartBtn) restartBtn.addEventListener('click', () => resetConversation());

    // Toggle Chat Window
    function toggleChatWindow() {
        if (!windowEl) return;
        if (windowEl.style.display === 'none' || windowEl.style.display === '') {
            windowEl.style.display = 'flex';
            if (messagesContainer.children.length === 0) {
                loadInitialChat();
            }
        } else {
            windowEl.style.display = 'none';
        }
    }

    // ================= 2. FETCH QUESTIONS & INITIALIZE =================
    function loadInitialChat() {
        showTypingIndicator();

        fetch("{{ route('chatbot.questions') }}")
            .then(res => res.json())
            .then(data => {
                removeTypingIndicator();
                if (data.success) {
                    faqQuestions = data.questions || [];
                    currentRole = data.role || 'guest';
                    currentUserName = data.user_name || null;

                    if (currentRole === 'student') {
                        roleBanner.className = 'chat-role-banner';
                        roleBanner.innerHTML = `<i class="fa-solid fa-graduation-cap"></i> <span>Akses Peserta: ${currentUserName || 'Mahasiswa Magang'}</span>`;
                    } else {
                        roleBanner.className = 'chat-role-banner guest-banner';
                        roleBanner.innerHTML = `<i class="fa-solid fa-user-shield"></i> <span>Akses: Publik / Tamu</span>`;
                    }

                    let greetingText = currentRole === 'student'
                        ? `Halo <strong>${currentUserName || 'Rekan Mahasiswa'}</strong>! 👋<br>Selamat datang di layanan <strong>Asisten Virtual PLTU Adipala</strong>. Silakan pilih topik informasi di bawah ini:`
                        : `Halo! Selamat datang di <strong>Portal Magang PLTU Adipala</strong>. 👋<br>Ada yang dapat kami bantu seputar pengajuan dan magang? Silakan klik pertanyaan di bawah ini:`;

                    appendBotMessage(greetingText);
                    renderFaqButtons(faqQuestions);
                }
            })
            .catch(err => {
                removeTypingIndicator();
                appendBotMessage('Mohon maaf, layanan asisten virtual sedang tidak dapat memuat daftar pertanyaan saat ini.');
            });
    }

    // ================= 3. RENDER FAQ BUTTONS =================
    function renderFaqButtons(questions) {
        const block = document.createElement('div');
        block.className = 'faq-options-block';
        block.id = 'current-faq-options';

        const title = document.createElement('div');
        title.className = 'faq-options-title';
        title.innerHTML = '<i class="fa-solid fa-list-check"></i> Daftar Pertanyaan:';
        block.appendChild(title);

        questions.forEach(q => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'faq-btn';
            btn.innerHTML = `
                <span class="faq-badge">${q.kode}</span>
                <span class="faq-text">${q.pertanyaan}</span>
                <i class="fa-solid fa-chevron-right faq-arrow"></i>
            `;
            btn.addEventListener('click', () => handleQuestionClick(q.id_faq, q.pertanyaan));
            block.appendChild(btn);
        });

        messagesContainer.appendChild(block);
        scrollToBottom();
    }

    // ================= 4. HANDLE QUESTION CLICK =================
    function handleQuestionClick(faqId, questionText) {
        const oldOptions = document.getElementById('current-faq-options');
        if (oldOptions) oldOptions.remove();

        appendUserMessage(questionText);
        showTypingIndicator();

        fetch("{{ route('chatbot.answer') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ faq_id: faqId })
        })
        .then(res => res.json())
        .then(data => {
            removeTypingIndicator();
            if (data.success && data.faq) {
                const answerHtml = `<strong>[${data.faq.kode}] ${data.faq.pertanyaan}</strong><br><br>${data.faq.jawaban}`;
                appendBotMessage(answerHtml);
                renderPostAnswerNavigation();
            } else {
                appendBotMessage('Mohon maaf, jawaban tidak ditemukan.');
                renderFaqButtons(faqQuestions);
            }
        })
        .catch(err => {
            removeTypingIndicator();
            appendBotMessage('Terjadi kendala koneksi saat mengambil jawaban.');
            renderFaqButtons(faqQuestions);
        });
    }

    // Post-Answer Nav
    function renderPostAnswerNavigation() {
        const navWrapper = document.createElement('div');
        navWrapper.className = 'post-answer-nav';
        navWrapper.id = 'post-answer-nav';

        const btnAgain = document.createElement('button');
        btnAgain.type = 'button';
        btnAgain.className = 'nav-chip';
        btnAgain.innerHTML = '<i class="fa-solid fa-list"></i> Pilih Pertanyaan Lain';
        btnAgain.addEventListener('click', () => {
            navWrapper.remove();
            renderFaqButtons(faqQuestions);
        });

        const btnRestart = document.createElement('button');
        btnRestart.type = 'button';
        btnRestart.className = 'nav-chip';
        btnRestart.innerHTML = '<i class="fa-solid fa-rotate-left"></i> Mulai Ulang';
        btnRestart.addEventListener('click', () => resetConversation());

        navWrapper.appendChild(btnAgain);
        navWrapper.appendChild(btnRestart);
        messagesContainer.appendChild(navWrapper);

        renderFaqButtons(faqQuestions);
        scrollToBottom();
    }

    // ================= 5. MESSAGE UTILITIES =================
    function appendBotMessage(html) {
        const msg = document.createElement('div');
        msg.className = 'chat-msg bot';
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        msg.innerHTML = `
            <div class="msg-bot-avatar"><i class="fa-solid fa-robot"></i></div>
            <div class="msg-content">
                <div>${html}</div>
                <span class="msg-time">${now}</span>
            </div>
        `;
        messagesContainer.appendChild(msg);
        scrollToBottom();
    }

    function appendUserMessage(text) {
        const msg = document.createElement('div');
        msg.className = 'chat-msg user';
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        msg.innerHTML = `
            <div class="msg-content">
                <div>${escapeHtml(text)}</div>
                <span class="msg-time">${now}</span>
            </div>
        `;
        messagesContainer.appendChild(msg);
        scrollToBottom();
    }

    function showTypingIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'chat-typing-indicator';
        indicator.className = 'chat-msg bot';
        indicator.innerHTML = `
            <div class="msg-bot-avatar"><i class="fa-solid fa-robot"></i></div>
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        messagesContainer.appendChild(indicator);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const el = document.getElementById('chat-typing-indicator');
        if (el) el.remove();
    }

    function resetConversation() {
        messagesContainer.innerHTML = '';
        loadInitialChat();
    }

    function scrollToBottom() {
        setTimeout(() => { messagesContainer.scrollTop = messagesContainer.scrollHeight; }, 30);
    }

    function escapeHtml(string) {
        const div = document.createElement('div');
        div.innerText = string;
        return div.innerHTML;
    }
})();
</script>
