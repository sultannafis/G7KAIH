{{-- ═══════════════════════════════════════════════════════════════
     GLOBAL MODERN CONFIRM / ALERT MODAL
     Pengganti confirm() dan alert() native browser.

     USAGE:
       Confirm:  g7Confirm('Pesan?', { onConfirm: () => { ... } })
       Alert:    g7Alert('Pesan info')
       Form:     g7ConfirmSubmit(formElement, 'Yakin hapus?')
                 — untuk onsubmit="return confirm(...)" patterns
     ═══════════════════════════════════════════════════════════════ --}}

<div id="g7-confirm-overlay"
     style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(15,23,42,.35);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);transition:opacity .2s ease"
     onclick="if(event.target===this)g7ModalClose(false)">
    <div style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:1rem;">
        <div id="g7-confirm-card"
             style="width:100%;max-width:420px;background:rgba(255,255,255,.95);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,.9);border-radius:24px;box-shadow:0 25px 60px rgba(14,165,233,.18),0 8px 24px rgba(0,0,0,.08);padding:2rem;transform:scale(.95) translateY(10px);opacity:0;transition:transform .25s cubic-bezier(.22,1,.36,1),opacity .2s ease">

            {{-- Icon --}}
            <div style="display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem">
                <div id="g7-confirm-icon"
                     style="width:56px;height:56px;border-radius:18px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#38bdf8,#0ea5e9);box-shadow:0 8px 20px rgba(14,165,233,.3)">
                    <svg id="g7-icon-confirm" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px;height:28px;color:#fff">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01"/>
                    </svg>
                    <svg id="g7-icon-warning" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px;height:28px;color:#fff;display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <svg id="g7-icon-danger" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px;height:28px;color:#fff;display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <svg id="g7-icon-success" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px;height:28px;color:#fff;display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg id="g7-icon-info" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px;height:28px;color:#fff;display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h3 id="g7-confirm-title"
                style="text-align:center;font-family:'Outfit',sans-serif;font-size:1.125rem;font-weight:800;color:#0f172a;margin:0 0 .5rem 0;line-height:1.3">
                Konfirmasi
            </h3>

            {{-- Message --}}
            <p id="g7-confirm-message"
               style="text-align:center;font-family:'Outfit',sans-serif;font-size:.9rem;font-weight:500;color:#64748b;margin:0 0 1.75rem 0;line-height:1.6">
            </p>

            {{-- Buttons --}}
            <div style="display:flex;gap:.75rem">
                <button type="button" id="g7-confirm-cancel"
                        onclick="g7ModalClose(false)"
                        style="flex:1;padding:.75rem 1rem;border-radius:14px;font-family:'Outfit',sans-serif;font-size:.875rem;font-weight:700;color:#64748b;background:rgba(241,245,249,.8);border:1px solid rgba(203,213,225,.6);cursor:pointer;transition:all .15s ease">
                    Batal
                </button>
                <button type="button" id="g7-confirm-ok"
                        onclick="g7ModalClose(true)"
                        style="flex:1;padding:.75rem 1rem;border-radius:14px;font-family:'Outfit',sans-serif;font-size:.875rem;font-weight:700;color:#fff;background:linear-gradient(135deg,#38bdf8,#0ea5e9);border:none;cursor:pointer;box-shadow:0 6px 16px rgba(14,165,233,.3);transition:all .15s ease">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #g7-confirm-cancel:hover { background:rgba(226,232,240,.9) !important; color:#334155 !important; }
    #g7-confirm-ok:hover { box-shadow:0 8px 24px rgba(14,165,233,.4) !important; transform:translateY(-1px); }
    #g7-confirm-ok:active { transform:scale(.97); }
    @keyframes g7ShakeSubtle {
        0%,100% { transform:translateX(0) }
        25% { transform:translateX(-3px) }
        75% { transform:translateX(3px) }
    }
</style>

<script>
(function(){
    let _resolve = null;
    let _onConfirm = null;
    let _onCancel = null;

    const overlay  = document.getElementById('g7-confirm-overlay');
    const card     = document.getElementById('g7-confirm-card');
    const titleEl  = document.getElementById('g7-confirm-title');
    const msgEl    = document.getElementById('g7-confirm-message');
    const cancelBtn= document.getElementById('g7-confirm-cancel');
    const okBtn    = document.getElementById('g7-confirm-ok');
    const iconEl   = document.getElementById('g7-confirm-icon');

    const icons = {
        confirm: document.getElementById('g7-icon-confirm'),
        warning: document.getElementById('g7-icon-warning'),
        danger:  document.getElementById('g7-icon-danger'),
        success: document.getElementById('g7-icon-success'),
        info:    document.getElementById('g7-icon-info'),
    };

    const themes = {
        confirm: { bg:'linear-gradient(135deg,#38bdf8,#0ea5e9)', shadow:'rgba(14,165,233,.3)', btnBg:'linear-gradient(135deg,#38bdf8,#0ea5e9)', btnShadow:'rgba(14,165,233,.3)' },
        warning: { bg:'linear-gradient(135deg,#fbbf24,#f59e0b)', shadow:'rgba(245,158,11,.3)', btnBg:'linear-gradient(135deg,#fbbf24,#f59e0b)', btnShadow:'rgba(245,158,11,.3)' },
        danger:  { bg:'linear-gradient(135deg,#fb7185,#e11d48)', shadow:'rgba(225,29,72,.3)',  btnBg:'linear-gradient(135deg,#fb7185,#e11d48)', btnShadow:'rgba(225,29,72,.3)' },
        success: { bg:'linear-gradient(135deg,#38bdf8,#0ea5e9)', shadow:'rgba(14,165,233,.3)', btnBg:'linear-gradient(135deg,#38bdf8,#0ea5e9)', btnShadow:'rgba(14,165,233,.3)' },
        info:    { bg:'linear-gradient(135deg,#a78bfa,#7c3aed)', shadow:'rgba(124,58,237,.3)', btnBg:'linear-gradient(135deg,#a78bfa,#7c3aed)', btnShadow:'rgba(124,58,237,.3)' },
    };

    function showIcon(type) {
        Object.values(icons).forEach(i => { if(i) i.style.display = 'none'; });
        if(icons[type]) icons[type].style.display = '';
    }

    function applyTheme(type) {
        const t = themes[type] || themes.confirm;
        iconEl.style.background = t.bg;
        iconEl.style.boxShadow  = '0 8px 20px ' + t.shadow;
        okBtn.style.background  = t.btnBg;
        okBtn.style.boxShadow   = '0 6px 16px ' + t.btnShadow;
    }

    function openModal() {
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            card.style.transform  = 'scale(1) translateY(0)';
            card.style.opacity    = '1';
        });
    }

    function closeModal() {
        card.style.transform = 'scale(.95) translateY(10px)';
        card.style.opacity   = '0';
        overlay.style.opacity = '0';
        setTimeout(() => {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }, 200);
    }

    /**
     * Detect type from message content
     */
    function detectType(msg) {
        const m = (msg || '').toLowerCase();
        if(m.includes('hapus') || m.includes('delete') || m.includes('perhatian')) return 'danger';
        if(m.includes('setujui') || m.includes('aktifkan') || m.includes('approve')) return 'success';
        if(m.includes('nonaktifkan') || m.includes('tolak') || m.includes('reject')) return 'warning';
        return 'confirm';
    }

    /**
     * Detect title from message content
     */
    function detectTitle(msg) {
        const m = (msg || '').toLowerCase();
        if(m.includes('hapus')) return 'Hapus Data';
        if(m.includes('setujui')) return 'Setujui';
        if(m.includes('nonaktifkan')) return 'Nonaktifkan';
        if(m.includes('aktifkan')) return 'Aktifkan';
        if(m.includes('generate')) return 'Generate';
        if(m.includes('sync')) return 'Sinkronisasi';
        if(m.includes('ubah status')) return 'Ubah Status';
        return 'Konfirmasi';
    }

    /**
     * Detect confirm button text from message
     */
    function detectConfirmText(msg) {
        const m = (msg || '').toLowerCase();
        if(m.includes('hapus')) return 'Ya, Hapus';
        if(m.includes('setujui')) return 'Ya, Setujui';
        if(m.includes('nonaktifkan')) return 'Ya, Nonaktifkan';
        if(m.includes('aktifkan')) return 'Ya, Aktifkan';
        return 'Ya, Lanjutkan';
    }

    /**
     * g7Confirm(message, options)
     */
    window.g7Confirm = function(message, options = {}) {
        const type = options.type || detectType(message);
        titleEl.textContent   = options.title || detectTitle(message);
        msgEl.innerHTML       = (message || '').replace(/\\n/g, '<br>').replace(/\n/g, '<br>');
        okBtn.textContent     = options.confirmText || detectConfirmText(message);
        cancelBtn.textContent = options.cancelText  || 'Batal';
        cancelBtn.style.display = '';

        showIcon(type);
        applyTheme(type);

        _onConfirm = options.onConfirm || null;
        _onCancel  = options.onCancel  || null;

        openModal();

        return new Promise(resolve => { _resolve = resolve; });
    };

    /**
     * g7Alert(message, options)
     */
    window.g7Alert = function(message, options = {}) {
        const type = options.type || 'info';
        titleEl.textContent   = options.title || 'Pemberitahuan';
        msgEl.innerHTML       = (message || '').replace(/\\n/g, '<br>').replace(/\n/g, '<br>');
        okBtn.textContent     = options.confirmText || 'OK';
        cancelBtn.style.display = 'none';

        showIcon(type);
        applyTheme(type);

        _onConfirm = options.onConfirm || null;
        _onCancel  = null;

        openModal();

        return new Promise(resolve => { _resolve = resolve; });
    };

    /**
     * g7ConfirmSubmit(form, message, options)
     */
    window.g7ConfirmSubmit = function(form, message, options = {}) {
        g7Confirm(message, {
            type: options.type || detectType(message),
            title: options.title || detectTitle(message),
            confirmText: options.confirmText || detectConfirmText(message),
            cancelText: options.cancelText || 'Batal',
            onConfirm: function() {
                const origHandler = form.onsubmit;
                form.onsubmit = null;
                form.submit();
                form.onsubmit = origHandler;
            }
        });
    };

    window.g7ModalClose = function(confirmed) {
        closeModal();
        if(confirmed) {
            if(_onConfirm) _onConfirm();
            if(_resolve) _resolve(true);
        } else {
            if(_onCancel) _onCancel();
            if(_resolve) _resolve(false);
        }
        _resolve = null;
        _onConfirm = null;
        _onCancel = null;
    };

    // ESC to close
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape' && overlay.style.display !== 'none') {
            g7ModalClose(false);
        }
    });

    // ═══════════════════════════════════════════════════════════
    // AUTO-INTERCEPT: Override native confirm() and alert()
    // This automatically converts ALL existing confirm()/alert()
    // calls to use the modern modal without editing individual files.
    // ═══════════════════════════════════════════════════════════

    // Override native alert()
    const _nativeAlert = window.alert;
    window.alert = function(msg) {
        g7Alert(msg, { type: 'info' });
    };

    // For forms using onsubmit="return confirm('...')" pattern:
    // We intercept at the form submit level using event delegation.
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if(!form || form.tagName !== 'FORM') return;

        // Check if form has onsubmit with confirm() pattern
        const onsubmitAttr = form.getAttribute('onsubmit');
        if(!onsubmitAttr || !onsubmitAttr.includes('confirm(')) return;

        // Already confirmed? Let it through
        if(form.dataset.g7Confirmed === 'true') {
            form.dataset.g7Confirmed = '';
            return; // allow submit
        }

        // Prevent the default submit
        e.preventDefault();
        e.stopImmediatePropagation();

        // Extract message from confirm('...')
        const match = onsubmitAttr.match(/confirm\s*\(\s*['"`]([\s\S]*?)['"`]\s*\)/);
        const msg = match ? match[1] : 'Apakah Anda yakin?';

        g7Confirm(msg, {
            onConfirm: function() {
                form.dataset.g7Confirmed = 'true';
                // Remove onsubmit temporarily to prevent re-trigger
                const orig = form.getAttribute('onsubmit');
                form.removeAttribute('onsubmit');
                form.submit();
                // Restore (in case submit is async/redirects)
                if(orig) setTimeout(() => form.setAttribute('onsubmit', orig), 100);
            }
        });
    }, true); // use capture phase to intercept before form's own handler

    // For buttons/links using onclick="return confirm('...')" pattern:
    document.addEventListener('click', function(e) {
        const el = e.target.closest('[onclick]');
        if(!el) return;

        const onclickAttr = el.getAttribute('onclick');
        if(!onclickAttr || !onclickAttr.includes('confirm(')) return;

        // Already confirmed? Let it through
        if(el.dataset.g7Confirmed === 'true') {
            el.dataset.g7Confirmed = '';
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        const match = onclickAttr.match(/confirm\s*\(\s*['"`]([\s\S]*?)['"`]\s*\)/);
        const msg = match ? match[1] : 'Apakah Anda yakin?';

        g7Confirm(msg, {
            onConfirm: function() {
                el.dataset.g7Confirmed = 'true';
                el.click();
            }
        });
    }, true);

    // Override native confirm() for JS-called confirm() (not inline)
    const _nativeConfirm = window.confirm;
    window.confirm = function(msg) {
        // For inline event handlers, we handle via event delegation above.
        // For JS-called confirm(), we show modal but return false immediately
        // and handle via g7Confirm. The caller must use g7Confirm() instead.
        // Fallback: show modal and return false to prevent immediate action
        g7Confirm(msg);
        return false;
    };
})();
</script>

