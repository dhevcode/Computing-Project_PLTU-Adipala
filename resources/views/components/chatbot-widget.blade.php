<!-- ================= CHATBOT ASISTEN VIRTUAL WIDGET ================= -->
<div id="pltu-chatbot-container">

    <!-- FLOATING MOVABLE BUBBLE TRIGGER -->
    <div id="chatbot-trigger" class="chatbot-bubble" title="Klik untuk membuka Asisten Virtual PLTU (Bisa digeser ke atas/bawah)">
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
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        position: relative;
        z-index: 999999;
    }

    .chatbot-bubble {
        position: fixed;
        right: 24px;
        top: 65%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #0A5F63 0%, #0E7478 50%, #11B5B3 100%);
        color: #ffffff;
        padding: 10px 14px 10px 16px;
        border-radius: 999px;
        box-shadow: 0 10px 28px rgba(10, 95, 99, 0.4), 0 2px 8px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        user-select: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        touch-action: none;
    }

    .chatbot-bubble:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 14px 34px rgba(10, 95, 99, 0.5);
    }

    .chatbot-bubble.dragging {
        transition: none !important;
        opacity: 0.9;
        cursor: grabbing !important;
        transform: translateY(-50%) scale(1.08);
    }

    .bubble-inner {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bubble-icon {
        position: relative;
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .bubble-icon .online-indicator {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background: #10B981;
        border: 2px solid #0A5F63;
        border-radius: 50%;
        animation: pulseIndicator 2s infinite;
    }

    @keyframes pulseIndicator {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.8; }
    }

    .bubble-label {
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }

    .drag-handle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 13px;
        padding-left: 2px;
        cursor: grab;
    }

    .drag-handle:hover {
        color: #ffffff;
    }

    /* ================= CHAT WINDOW ================= */
    .chatbot-window {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 380px;
        max-width: calc(100vw - 32px);
        height: 580px;
        max-height: calc(100vh - 40px);
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.22), 0 4px 15px rgba(10, 95, 99, 0.15);
        border: 1px solid rgba(10, 95, 99, 0.12);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: chatWindowOpen 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 9999999;
    }

    @keyframes chatWindowOpen {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Header */
    .chat-header {
        background: linear-gradient(135deg, #0A5F63 0%, #0D7377 100%);
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
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .chat-avatar .online-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 10px;
        height: 10px;
        background: #10B981;
        border: 2px solid #0A5F63;
        border-radius: 50%;
    }

    .chat-header-info h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.2;
    }

    .chat-header-info p {
        margin: 3px 0 0 0;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.82);
    }

    .chat-header-actions {
        display: flex;
        align-items: center;
        gap: 6px;
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
        transition: background 0.2s;
    }

    .chat-tool-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Role Banner */
    .chat-role-banner {
        background: #F0FDF4;
        border-bottom: 1px solid #DCFCE7;
        color: #166534;
        font-size: 11px;
        font-weight: 600;
        padding: 6px 16px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chat-role-banner.guest-banner {
        background: #EFF6FF;
        border-bottom: 1px solid #DBEAFE;
        color: #1E40AF;
    }

    /* Chat Messages Body */
    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #F8FAFC;
        display: flex;
        flex-direction: column;
        gap: 14px;
        scroll-behavior: smooth;
    }

    .chat-body::-webkit-scrollbar {
        width: 6px;
    }
    .chat-body::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 10px;
    }

    /* Message Bubbles */
    .chat-msg {
        display: flex;
        gap: 10px;
        max-width: 90%;
        animation: msgFadeIn 0.22s ease-out;
    }

    @keyframes msgFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chat-msg.bot {
        align-self: flex-start;
    }

    .chat-msg.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .msg-bot-avatar {
        width: 30px;
        height: 30px;
        background: #0A5F63;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .msg-content {
        padding: 12px 15px;
        border-radius: 16px;
        font-size: 12.5px;
        line-height: 1.55;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .chat-msg.bot .msg-content {
        background: #ffffff;
        color: #1E293B;
        border-top-left-radius: 4px;
        border: 1px solid #E2E8F0;
    }

    .chat-msg.user .msg-content {
        background: linear-gradient(135deg, #0A5F63 0%, #0E7478 100%);
        color: #ffffff;
        border-top-right-radius: 4px;
    }

    .msg-time {
        display: block;
        font-size: 10px;
        color: #94A3B8;
        margin-top: 5px;
        text-align: right;
    }

    .chat-msg.user .msg-time {
        color: rgba(255, 255, 255, 0.75);
    }

    /* Typing Indicator */
    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 10px 14px;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        width: fit-content;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        background: #0A5F63;
        border-radius: 50%;
        animation: typingAnimation 1.4s infinite ease-in-out;
    }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typingAnimation {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1.1); opacity: 1; }
    }

    /* FAQ Quick Reply Buttons Section */
    .faq-options-block {
        margin-top: 4px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        animation: msgFadeIn 0.25s ease-out;
    }

    .faq-options-title {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .faq-btn {
        background: #ffffff;
        border: 1px solid #CBD5E1;
        color: #0F172A;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 500;
        text-align: left;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        transition: all 0.18s ease;
        line-height: 1.4;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .faq-btn:hover {
        background: #F0FDF4;
        border-color: #0A5F63;
        color: #0A5F63;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(10, 95, 99, 0.12);
    }

    .faq-badge {
        background: #E0F2FE;
        color: #0369A1;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .faq-btn:hover .faq-badge {
        background: #0A5F63;
        color: #ffffff;
    }

    .faq-text {
        flex: 1;
    }

    .faq-arrow {
        color: #94A3B8;
        font-size: 11px;
        margin-top: 3px;
        transition: transform 0.2s;
    }

    .faq-btn:hover .faq-arrow {
        color: #0A5F63;
        transform: translateX(2px);
    }

    /* Action Chips after Answer */
    .post-answer-nav {
        margin-top: 6px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .nav-chip {
        background: #E2E8F0;
        color: #334155;
        border: none;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }

    .nav-chip:hover {
        background: #0A5F63;
        color: #ffffff;
    }

    /* Footer Note */
    .chat-footer-note {
        padding: 10px 14px;
        background: #F1F5F9;
        border-top: 1px solid #E2E8F0;
        font-size: 11px;
        color: #64748B;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.35;
    }

    .chat-footer-note i {
        color: #0A5F63;
        font-size: 12px;
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .chatbot-window {
            right: 12px;
            bottom: 12px;
            width: calc(100vw - 24px);
            height: calc(100vh - 24px);
            border-radius: 16px;
        }
        .chatbot-bubble {
            right: 14px;
            padding: 8px 12px;
        }
        .bubble-label {
            display: none;
        }
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

    // Restore saved vertical position
    const savedTop = localStorage.getItem('pltu_chatbot_top');
    if (savedTop && trigger) {
        trigger.style.top = savedTop;
    }

    // ================= 1. DRAG / MOVE LOGIC =================
    if (trigger) {
        const startDrag = (clientY) => {
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

        // Mouse events
        trigger.addEventListener('mousedown', (e) => {
            startDrag(e.clientY);
        });
        window.addEventListener('mousemove', (e) => {
            onDrag(e.clientY);
        });
        window.addEventListener('mouseup', () => {
            endDrag();
        });

        // Touch events
        trigger.addEventListener('touchstart', (e) => {
            if (e.touches.length > 0) {
                startDrag(e.touches[0].clientY);
            }
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (e.touches.length > 0) {
                onDrag(e.touches[0].clientY);
            }
        }, { passive: true });

        window.addEventListener('touchend', () => {
            endDrag();
        });

        // Click to toggle window (only if not dragged)
        trigger.addEventListener('click', (e) => {
            if (hasDragged) {
                e.preventDefault();
                hasDragged = false;
                return;
            }
            toggleChatWindow();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            windowEl.style.display = 'none';
        });
    }

    if (restartBtn) {
        restartBtn.addEventListener('click', () => {
            resetConversation();
        });
    }

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

                    // Update Role Banner
                    if (currentRole === 'student') {
                        roleBanner.className = 'chat-role-banner';
                        roleBanner.innerHTML = `<i class="fa-solid fa-graduation-cap"></i> <span>Akses Peserta: ${currentUserName || 'Mahasiswa Magang'}</span>`;
                    } else {
                        roleBanner.className = 'chat-role-banner guest-banner';
                        roleBanner.innerHTML = `<i class="fa-solid fa-user-shield"></i> <span>Akses: Publik / Tamu</span>`;
                    }

                    // Bot Greeting
                    let greetingText = '';
                    if (currentRole === 'student') {
                        greetingText = `Halo <strong>${currentUserName || 'Rekan Mahasiswa'}</strong>! 👋<br>Selamat datang di layanan <strong>Asisten Virtual PLTU Adipala</strong>. Silakan pilih topik informasi atau pertanyaan di bawah ini:`;
                    } else {
                        greetingText = `Halo! Selamat datang di <strong>Portal Magang PLTU Adipala</strong>. 👋<br>Ada yang dapat kami bantu seputar informasi pengajuan dan pelaksanaan magang? Silakan klik pertanyaan yang tersedia di bawah ini:`;
                    }

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
            btn.addEventListener('click', () => {
                handleQuestionClick(q.id_faq, q.pertanyaan);
            });
            block.appendChild(btn);
        });

        messagesContainer.appendChild(block);
        scrollToBottom();
    }

    // ================= 4. HANDLE QUESTION CLICK & FETCH ANSWER =================
    function handleQuestionClick(faqId, questionText) {
        // Disable or remove current options
        const oldOptions = document.getElementById('current-faq-options');
        if (oldOptions) {
            oldOptions.remove();
        }

        // 1. Display User Message Bubble
        appendUserMessage(questionText);

        // 2. Show Typing Indicator
        showTypingIndicator();

        // 3. Fetch Answer from Backend
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
                // Display Bot Answer
                const answerHtml = `<strong>[${data.faq.kode}] ${data.faq.pertanyaan}</strong><br><br>${data.faq.jawaban}`;
                appendBotMessage(answerHtml);

                // Append Navigation Chips & Options List again
                renderPostAnswerNavigation();
            } else {
                appendBotMessage('Mohon maaf, jawaban untuk pertanyaan ini tidak ditemukan.');
                renderFaqButtons(faqQuestions);
            }
        })
        .catch(err => {
            removeTypingIndicator();
            appendBotMessage('Terjadi kendala koneksi saat mengambil jawaban. Silakan coba kembali.');
            renderFaqButtons(faqQuestions);
        });
    }

    // Post-Answer Navigation Chips
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
        btnRestart.addEventListener('click', () => {
            resetConversation();
        });

        navWrapper.appendChild(btnAgain);
        navWrapper.appendChild(btnRestart);
        messagesContainer.appendChild(navWrapper);

        // Render question list directly underneath for maximum convenience
        renderFaqButtons(faqQuestions);
        scrollToBottom();
    }

    // ================= 5. MESSAGE UTILITIES =================
    function appendBotMessage(html) {
        const msg = document.createElement('div');
        msg.className = 'chat-msg bot';
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        msg.innerHTML = `
            <div class="msg-bot-avatar">
                <i class="fa-solid fa-robot"></i>
            </div>
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
            <div class="msg-bot-avatar">
                <i class="fa-solid fa-robot"></i>
            </div>
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
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 30);
    }

    function escapeHtml(string) {
        const div = document.createElement('div');
        div.innerText = string;
        return div.innerHTML;
    }
})();
</script>
