{{--
    resources/views/components/ai-chatbot.blade.php
    Include di layouts/app.blade.php sebelum </body>
    Hanya tampil jika AI diaktifkan oleh masteradmin.
--}}

@php
    $aiSetting = \App\Models\AiSetting::instance();
@endphp

@if($aiSetting->is_enabled)
<div id="g7-ai-wrapper" style="position:fixed;bottom:24px;right:24px;z-index:9999;font-family:'Outfit',sans-serif;">

    {{-- Tombol buka/tutup --}}
    <button id="g7-ai-toggle" onclick="g7AiToggle()"
        style="width:52px;height:52px;border-radius:50%;
               background:linear-gradient(135deg,#38bdf8,#0ea5e9);
               border:none;cursor:pointer;color:white;
               box-shadow:0 4px 20px rgba(14,165,233,.45);
               display:flex;align-items:center;justify-content:center;
               transition:transform .2s ease;">
        <span id="g7-ai-icon-open" style="display:flex;align-items:center;justify-content:center;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
        </span>
        <span id="g7-ai-icon-close" style="display:none;align-items:center;justify-content:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </span>
    </button>

    {{-- Panel Chatbot --}}
    <div id="g7-ai-panel"
        style="display:none;flex-direction:column;
               width:90vw;max-width:380px;
               height:70vh;max-height:560px;
               position:absolute;bottom:64px;right:0;
               border-radius:16px;overflow:hidden;
               background:#ffffff;
               border:1px solid rgba(0,0,0,.09);
               box-shadow:0 12px 48px rgba(0,0,0,.16),0 2px 8px rgba(0,0,0,.06);">

        {{-- Header --}}
        <div style="background:#fff;padding:13px 16px;border-bottom:1px solid rgba(0,0,0,.07);flex-shrink:0;display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#38bdf8,#0ea5e9);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>
                </svg>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:700;font-size:14px;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $aiSetting->app_name ?? 'AI Assistant' }}</div>
                <div style="font-size:11px;color:#6b7280;display:flex;align-items:center;gap:5px;margin-top:1px;">
                    <span style="width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block;flex-shrink:0;"></span>
                    Siap membantu
                </div>
            </div>
            <button onclick="g7ClearChat()" title="Bersihkan percakapan"
                style="background:none;border:none;cursor:pointer;color:#9ca3af;padding:5px;border-radius:8px;
                       display:flex;align-items:center;justify-content:center;transition:color .15s,background .15s;flex-shrink:0;"
                onmouseover="this.style.color='#374151';this.style.background='#f3f4f6'"
                onmouseout="this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </button>
        </div>

        {{-- Area Pesan --}}
        <div id="g7-ai-messages"
            style="flex:1;overflow-y:auto;padding:16px 14px;display:flex;flex-direction:column;gap:12px;background:#f9fafb;">
            <div class="g7-msg-bot">
                Halo! Saya AI Assistant untuk <strong>{{ $aiSetting->app_name ?? 'aplikasi ini' }}</strong>.<br>
                Ada yang bisa saya bantu? Anda juga bisa melampirkan gambar.
            </div>
        </div>

        {{-- Preview gambar antrian (ditahan sebelum dikirim) --}}
        <div id="g7-image-preview-area"
            style="display:none;padding:8px 12px;border-top:1px solid rgba(0,0,0,.06);
                   background:#fff;flex-wrap:wrap;gap:8px;flex-shrink:0;">
        </div>

        {{-- Input Area --}}
        <div style="padding:10px 12px 12px;border-top:1px solid rgba(0,0,0,.07);background:#fff;flex-shrink:0;">

            {{-- Kotak input --}}
            <div id="g7-input-container"
                 style="display:flex;align-items:flex-end;gap:6px;
                        background:#f3f4f6;border-radius:12px;padding:7px 10px;
                        border:1.5px solid transparent;transition:border-color .2s,background .2s;"
                 onfocusin="this.style.borderColor='#38bdf8';this.style.background='#fff'"
                 onfocusout="this.style.borderColor='transparent';this.style.background='#f3f4f6'">

                {{-- Tombol upload gambar --}}
                <button onclick="document.getElementById('g7-file-input').click()" title="Lampirkan gambar"
                    style="background:none;border:none;cursor:pointer;color:#9ca3af;padding:2px;
                           flex-shrink:0;display:flex;align-items:center;transition:color .15s;"
                    onmouseover="this.style.color='#0ea5e9'" onmouseout="this.style.color='#9ca3af'">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </button>
                <input type="file" id="g7-file-input" accept="image/*" multiple style="display:none;" onchange="g7HandleImageUpload(this)">

                {{-- Textarea --}}
                <textarea id="g7-ai-input"
                    placeholder="Ketik pesan..."
                    rows="1"
                    style="flex:1;background:transparent;border:none;outline:none;
                           font-size:13.5px;color:#111827;font-family:inherit;
                           resize:none;line-height:1.55;max-height:120px;overflow-y:auto;
                           padding:2px 0;"
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();g7AiSend();}"
                    oninput="g7AutoResize(this);g7UpdateSendBtn();"></textarea>

                {{-- Tombol kirim --}}
                <button id="g7-send-btn" onclick="g7AiSend()"
                    style="background:linear-gradient(135deg,#38bdf8,#0ea5e9);color:white;border:none;
                           border-radius:8px;width:32px;height:32px;cursor:pointer;
                           display:flex;align-items:center;justify-content:center;
                           flex-shrink:0;transition:opacity .15s,transform .1s;opacity:.45;"
                    onmouseover="if(g7CanSend())this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </button>
            </div>

            <p style="font-size:10px;color:#9ca3af;text-align:center;margin-top:7px;line-height:1.4;">
                Enter kirim &middot; Shift+Enter baris baru
            </p>
        </div>
    </div>
</div>

{{-- ── Styles ── --}}
<style>
.g7-msg-bot {
    background:#ffffff;
    color:#111827;
    padding:10px 14px;
    border-radius:4px 12px 12px 12px;
    font-size:13.5px;
    max-width:92%;
    line-height:1.6;
    box-shadow:0 1px 3px rgba(0,0,0,.06);
    border:1px solid rgba(0,0,0,.06);
    word-break:break-word;
}
.g7-msg-user {
    background:linear-gradient(135deg,#38bdf8,#0ea5e9);
    color:white;
    padding:10px 14px;
    border-radius:12px 12px 4px 12px;
    font-size:13.5px;
    max-width:92%;
    line-height:1.6;
    align-self:flex-end;
    margin-left:auto;
    word-break:break-word;
}
.g7-msg-typing {
    background:#ffffff;
    padding:12px 16px;
    border-radius:4px 12px 12px 12px;
    max-width:80px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow:0 1px 3px rgba(0,0,0,.06);
    display:flex;align-items:center;gap:5px;
}
.g7-typing-dot {
    width:6px;height:6px;
    background:#9ca3af;border-radius:50%;
    animation:g7bounce .9s infinite;
}
.g7-typing-dot:nth-child(2){animation-delay:.18s;}
.g7-typing-dot:nth-child(3){animation-delay:.36s;}
@keyframes g7bounce {
    0%,60%,100%{transform:translateY(0);opacity:.6;}
    30%{transform:translateY(-5px);opacity:1;}
}

/* Thumbnail gambar di antrian */
.g7-img-thumb {
    position:relative;
    width:60px;height:60px;
    border-radius:8px;overflow:hidden;
    border:1.5px solid rgba(56,189,248,.35);
    flex-shrink:0;
}
.g7-img-thumb img { width:100%;height:100%;object-fit:cover; }
.g7-img-remove {
    position:absolute;top:3px;right:3px;
    width:18px;height:18px;border-radius:50%;
    background:rgba(0,0,0,.6);color:white;
    border:none;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
}

/* Gambar di bubble pesan */
.g7-bubble-img {
    max-width:200px;border-radius:10px;
    margin-top:6px;display:block;
    border:1px solid rgba(255,255,255,.25);
}

/* Scrollbar */
#g7-ai-messages::-webkit-scrollbar{width:4px;}
#g7-ai-messages::-webkit-scrollbar-track{background:transparent;}
#g7-ai-messages::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:99px;}
</style>

{{-- ── Script ── --}}
<script>
/* ─── State ─── */
let g7PendingImages = []; // [{file, dataUrl, mime}]

/* ─── Toggle buka/tutup panel ─── */
function g7AiToggle() {
    const panel = document.getElementById('g7-ai-panel');
    const open  = panel.style.display === 'none' || panel.style.display === '';
    panel.style.display = open ? 'flex' : 'none';
    document.getElementById('g7-ai-icon-open').style.display  = open ? 'none'  : 'flex';
    document.getElementById('g7-ai-icon-close').style.display = open ? 'flex'  : 'none';
    if (open) setTimeout(() => document.getElementById('g7-ai-input')?.focus(), 150);
}

/* ─── Bersihkan percakapan ─── */
function g7ClearChat() {
    const box = document.getElementById('g7-ai-messages');
    box.innerHTML = '';
    g7AppendMsg('Percakapan dibersihkan. Ada yang bisa saya bantu?', 'bot');
    g7PendingImages = [];
    g7RenderPreviews();
}

/* ─── Auto resize textarea ─── */
function g7AutoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

/* ─── Cek apakah bisa kirim ─── */
function g7CanSend() {
    const input = document.getElementById('g7-ai-input');
    return (input?.value.trim().length > 0) || g7PendingImages.length > 0;
}

/* ─── Update opacity tombol kirim ─── */
function g7UpdateSendBtn() {
    const btn = document.getElementById('g7-send-btn');
    if (btn) btn.style.opacity = g7CanSend() ? '1' : '.45';
}

/* ─── Handle upload gambar → tahan di antrian ─── */
function g7HandleImageUpload(input) {
    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            g7PendingImages.push({ file, dataUrl: e.target.result, mime: file.type });
            g7RenderPreviews();
            g7UpdateSendBtn();
        };
        reader.readAsDataURL(file);
    });
    input.value = ''; // reset supaya bisa pilih file sama lagi
}

/* ─── Render thumbnails gambar yang tertahan ─── */
function g7RenderPreviews() {
    const area = document.getElementById('g7-image-preview-area');
    if (g7PendingImages.length === 0) {
        area.style.display = 'none';
        area.innerHTML = '';
        return;
    }
    area.style.display = 'flex';
    area.innerHTML = '';
    g7PendingImages.forEach((img, idx) => {
        const wrap = document.createElement('div');
        wrap.className = 'g7-img-thumb';
        wrap.innerHTML = `
            <img src="${img.dataUrl}" alt="preview">
            <button class="g7-img-remove" onclick="g7RemoveImg(${idx})" title="Hapus gambar">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>`;
        area.appendChild(wrap);
    });
}

function g7RemoveImg(idx) {
    g7PendingImages.splice(idx, 1);
    g7RenderPreviews();
    g7UpdateSendBtn();
}

/* ─── Append bubble pesan ─── */
function g7AppendMsg(html, type) {
    const box = document.getElementById('g7-ai-messages');
    const div = document.createElement('div');
    div.className = 'g7-msg-' + type;
    div.innerHTML  = html;
    box.appendChild(div);
    box.scrollTop  = box.scrollHeight;
    return div;
}

/* ─── Kirim pesan + gambar ke server ─── */
async function g7AiSend() {
    const input   = document.getElementById('g7-ai-input');
    const message = input.value.trim();
    const images  = [...g7PendingImages];

    if (!message && images.length === 0) return;

    // Tampilkan bubble user
    let userHtml = message ? escHtml(message).replace(/\n/g, '<br>') : '';
    images.forEach(img => {
        userHtml += `<img src="${img.dataUrl}" class="g7-bubble-img" alt="lampiran">`;
    });
    g7AppendMsg(userHtml, 'user');

    // Reset input & antrian
    input.value = '';
    input.style.height = 'auto';
    g7PendingImages = [];
    g7RenderPreviews();
    g7UpdateSendBtn();

    // Typing indicator
    const typingWrap = document.createElement('div');
    typingWrap.className = 'g7-msg-typing';
    typingWrap.innerHTML = '<div class="g7-typing-dot"></div><div class="g7-typing-dot"></div><div class="g7-typing-dot"></div>';
    const msgBox = document.getElementById('g7-ai-messages');
    msgBox.appendChild(typingWrap);
    msgBox.scrollTop = 9999;

    // Siapkan payload gambar (strip header data:..;base64, — kirim base64 murni)
    const imagePayload = images.map(img => ({
        data: img.dataUrl.split(',')[1],
        mime: img.mime,
    }));

    try {
        const resp = await fetch('/ai/chat', {
            method : 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : (document.querySelector('meta[name="csrf-token"]') || {}).content || '',
            },
            body: JSON.stringify({
                message : message || 'Tolong analisis gambar ini.',
                context : 'general',
                field   : '',
                images  : imagePayload,
            }),
        });

        typingWrap.remove();
        const data = await resp.json();

        if (data.error) {
            g7AppendMsg('<span style="color:#ef4444;">'+escHtml(data.error)+'</span>', 'bot');
            return;
        }

        const reply = (data.reply || '').trim();
        g7AppendMsg(escHtml(reply).replace(/\n/g, '<br>'), 'bot');

    } catch (err) {
        typingWrap.remove();
        g7AppendMsg('<span style="color:#ef4444;">Koneksi gagal. Coba lagi ya.</span>', 'bot');
    }
}

/* ─── Escape HTML ─── */
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
</script>
@endif