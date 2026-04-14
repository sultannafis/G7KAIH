{{-- ══════════════════════════════════════════════════════════════
     GLOBAL TOAST NOTIFICATION — top-right, modern glassmorphism
     Supports: success · error · warning · info
     Auto-dismiss with animated progress bar
     ══════════════════════════════════════════════════════════════ --}}

<div id="g7-toast-container" style="
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    pointer-events: none;
    max-width: 420px;
    width: calc(100% - 48px);
"></div>

<style>
    /* ── Toast Base ─────────────────────────────── */
    .g7-toast {
        pointer-events: all;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 20px 18px 18px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow:
            0 12px 40px rgba(0, 0, 0, 0.08),
            0 4px 12px rgba(0, 0, 0, 0.04),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
        position: relative;
        overflow: hidden;
        transform: translateX(120%);
        opacity: 0;
        animation: g7ToastSlideIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        font-family: 'Outfit', sans-serif;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.3s ease;
    }

    .g7-toast.g7-toast-out {
        animation: g7ToastSlideOut 0.4s cubic-bezier(0.55, 0, 1, 0.45) forwards;
    }

    /* ── Icon circle ───────────────────────────── */
    .g7-toast-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        position: relative;
    }

    .g7-toast-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 14px;
        opacity: 0.12;
    }

    /* ── Content ────────────────────────────────── */
    .g7-toast-body {
        flex: 1;
        min-width: 0;
    }

    .g7-toast-title {
        font-weight: 700;
        font-size: 14px;
        margin: 0 0 3px 0;
        line-height: 1.3;
    }

    .g7-toast-message {
        font-size: 13px;
        line-height: 1.5;
        margin: 0;
        opacity: 0.78;
        word-break: break-word;
    }

    /* ── Close button ──────────────────────────── */
    .g7-toast-close {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        margin: -4px -4px 0 0;
        border-radius: 10px;
        color: rgba(0, 0, 0, 0.35);
        font-size: 16px;
        line-height: 1;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
    }

    .g7-toast-close:hover {
        background: rgba(0, 0, 0, 0.06);
        color: rgba(0, 0, 0, 0.6);
    }

    /* ── Progress bar ──────────────────────────── */
    .g7-toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        border-radius: 0 0 18px 18px;
        animation: g7ToastProgress var(--toast-duration, 5s) linear forwards;
    }

    /* ── Type variations ───────────────────────── */
    /* Success */
    .g7-toast-success { border-left: 4px solid #0ea5e9; }
    .g7-toast-success .g7-toast-icon { color: #0ea5e9; background: rgba(14, 165, 233, 0.1); }
    .g7-toast-success .g7-toast-icon::before { background: #0ea5e9; }
    .g7-toast-success .g7-toast-title { color: #0369a1; }
    .g7-toast-success .g7-toast-message { color: #0c4a6e; }
    .g7-toast-success .g7-toast-progress { background: linear-gradient(90deg, #0ea5e9, #38bdf8); }

    /* Error */
    .g7-toast-error { border-left: 4px solid #ef4444; }
    .g7-toast-error .g7-toast-icon { color: #ef4444; background: rgba(239, 68, 68, 0.1); }
    .g7-toast-error .g7-toast-icon::before { background: #ef4444; }
    .g7-toast-error .g7-toast-title { color: #991b1b; }
    .g7-toast-error .g7-toast-message { color: #7f1d1d; }
    .g7-toast-error .g7-toast-progress { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* Warning */
    .g7-toast-warning { border-left: 4px solid #f59e0b; }
    .g7-toast-warning .g7-toast-icon { color: #f59e0b; background: rgba(245, 158, 11, 0.1); }
    .g7-toast-warning .g7-toast-icon::before { background: #f59e0b; }
    .g7-toast-warning .g7-toast-title { color: #92400e; }
    .g7-toast-warning .g7-toast-message { color: #78350f; }
    .g7-toast-warning .g7-toast-progress { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    /* Info */
    .g7-toast-info { border-left: 4px solid #3b82f6; }
    .g7-toast-info .g7-toast-icon { color: #3b82f6; background: rgba(59, 130, 246, 0.1); }
    .g7-toast-info .g7-toast-icon::before { background: #3b82f6; }
    .g7-toast-info .g7-toast-title { color: #1e3a8a; }
    .g7-toast-info .g7-toast-message { color: #1e40af; }
    .g7-toast-info .g7-toast-progress { background: linear-gradient(90deg, #3b82f6, #60a5fa); }

    /* ── Animations ────────────────────────────── */
    @keyframes g7ToastSlideIn {
        0%   { transform: translateX(120%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }

    @keyframes g7ToastSlideOut {
        0%   { transform: translateX(0); opacity: 1; max-height: 200px; margin-bottom: 0; }
        60%  { transform: translateX(110%); opacity: 0; max-height: 200px; }
        100% { transform: translateX(110%); opacity: 0; max-height: 0; padding: 0; margin: 0; border: 0; }
    }

    @keyframes g7ToastProgress {
        0%   { width: 100%; }
        100% { width: 0%; }
    }

    /* ── Dark Mode ─────────────────────────────── */
    @media (prefers-color-scheme: dark) {
        .g7-toast {
            background: rgba(15, 23, 42, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow:
                0 12px 40px rgba(0, 0, 0, 0.35),
                0 4px 12px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .g7-toast-close { color: rgba(255, 255, 255, 0.35); }
        .g7-toast-close:hover { background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.7); }

        .g7-toast-success .g7-toast-title { color: #7dd3fc; }
        .g7-toast-success .g7-toast-message { color: #bae6fd; }

        .g7-toast-error .g7-toast-title { color: #fca5a5; }
        .g7-toast-error .g7-toast-message { color: #fecaca; }

        .g7-toast-warning .g7-toast-title { color: #fcd34d; }
        .g7-toast-warning .g7-toast-message { color: #fde68a; }

        .g7-toast-info .g7-toast-title { color: #93c5fd; }
        .g7-toast-info .g7-toast-message { color: #bfdbfe; }
    }

    /* ── Responsive ────────────────────────────── */
    @media (max-width: 480px) {
        #g7-toast-container {
            top: 12px;
            right: 12px;
            left: 12px;
            max-width: none;
            width: auto;
        }
        .g7-toast {
            border-radius: 14px;
            padding: 14px 16px 14px 14px;
        }
        .g7-toast-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 12px;
            font-size: 16px;
        }
    }
</style>

<script>
    /**
     * G7 Toast Notification System
     * Usage: G7Toast.show({ type: 'success', title: '...', message: '...' })
     */
    window.G7Toast = {
        container: null,

        init() {
            this.container = document.getElementById('g7-toast-container');
        },

        getIcon(type) {
            const icons = {
                success: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                error:   '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                warning: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                info:    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            };
            return icons[type] || icons.info;
        },

        getTitle(type) {
            const titles = {
                success: 'Berhasil!',
                error:   'Gagal!',
                warning: 'Perhatian!',
                info:    'Informasi'
            };
            return titles[type] || 'Notifikasi';
        },

        show({ type = 'info', title = null, message = '', duration = 5000 }) {
            if (!this.container) this.init();

            const toast = document.createElement('div');
            toast.className = `g7-toast g7-toast-${type}`;
            toast.style.setProperty('--toast-duration', `${duration}ms`);

            toast.innerHTML = `
                <div class="g7-toast-icon">${this.getIcon(type)}</div>
                <div class="g7-toast-body">
                    <p class="g7-toast-title">${title || this.getTitle(type)}</p>
                    <p class="g7-toast-message">${message}</p>
                </div>
                <button class="g7-toast-close" onclick="G7Toast.dismiss(this.closest('.g7-toast'))" aria-label="Tutup">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <div class="g7-toast-progress"></div>
            `;

            this.container.appendChild(toast);

            // Auto-dismiss
            const timer = setTimeout(() => this.dismiss(toast), duration);
            toast._timer = timer;

            // Pause on hover
            toast.addEventListener('mouseenter', () => {
                clearTimeout(toast._timer);
                const progress = toast.querySelector('.g7-toast-progress');
                if (progress) progress.style.animationPlayState = 'paused';
            });

            toast.addEventListener('mouseleave', () => {
                const progress = toast.querySelector('.g7-toast-progress');
                if (progress) progress.style.animationPlayState = 'running';
                toast._timer = setTimeout(() => this.dismiss(toast), 2000);
            });

            return toast;
        },

        dismiss(toast) {
            if (!toast || toast._dismissed) return;
            toast._dismissed = true;
            clearTimeout(toast._timer);
            toast.classList.add('g7-toast-out');
            toast.addEventListener('animationend', () => toast.remove());
        }
    };

    // ── Auto-show Laravel session flash messages ──────────
    document.addEventListener('DOMContentLoaded', function () {
        G7Toast.init();

        @if($errors->any())
            G7Toast.show({
                type: 'error',
                message: @json($errors->first()),
                duration: 8000
            });
        @endif

        @if(session('info'))
            G7Toast.show({
                type: 'info',
                message: @json(session('info')),
                duration: 6000
            });
        @endif

        @if(session('success'))
            G7Toast.show({
                type: 'success',
                message: @json(session('success')),
                duration: 5000
            });
        @endif

        @if(session('error'))
            G7Toast.show({
                type: 'error',
                message: @json(session('error')),
                duration: 8000
            });
        @endif

        @if(session('warning'))
            G7Toast.show({
                type: 'warning',
                message: @json(session('warning')),
                duration: 6000
            });
        @endif

        @if(session('status'))
            G7Toast.show({
                type: 'info',
                message: @json(session('status')),
                duration: 5000
            });
        @endif

    });
</script>
