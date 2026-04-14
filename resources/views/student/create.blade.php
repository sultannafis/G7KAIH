<x-app-layout>
    <x-slot name="header">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

            * { font-family: 'Plus Jakarta Sans', sans-serif; }

            /* ═══════════════════════════════════════════════
               KEYFRAMES
            ═══════════════════════════════════════════════ */
            @keyframes floatUp      { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
            @keyframes shutter      { 0%{opacity:0} 15%{opacity:.85} 100%{opacity:0} }
            @keyframes scanLine     { 0%{top:0} 100%{top:calc(100% - 58px)} }
            @keyframes recordPulse  { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.35;transform:scale(.88)} }
            @keyframes spinRing     { to{transform:rotate(360deg)} }
            @keyframes cornerGlow   { 0%,100%{opacity:.65} 50%{opacity:1} }

            /* ═══════════════════════════════════════════════
               LAYOUT
            ═══════════════════════════════════════════════ */
            .header-in { animation: floatUp .4s cubic-bezier(.22,1,.36,1) both }

            .form-section {
                background: rgba(255,255,255,.78);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1.5px solid rgba(255,255,255,.95);
                box-shadow: 0 4px 32px rgba(14,165,233,.06), 0 1px 3px rgba(0,0,0,.04);
                border-radius: 20px;
                overflow: hidden;
            }

            .section-head {
                padding: 13px 20px;
                border-bottom: 1px solid rgba(186,230,253,.25);
                display: flex; align-items: center; gap: 10px;
            }
            .section-icon {
                width: 30px; height: 30px; border-radius: 9px;
                background: linear-gradient(135deg, rgba(224,242,254,.95), rgba(186,230,253,.65));
                display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            }
            .section-title { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .13em; color: #0284c7; }
            .section-body  { padding: 18px 20px; }

            .form-textarea {
                width: 100%; padding: 12px 15px; border-radius: 12px;
                font-size: .875rem; font-weight: 500; color: #0c4a6e;
                background: rgba(240,249,255,.55);
                border: 1.5px solid rgba(186,230,253,.6);
                resize: vertical; min-height: 90px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                transition: all .2s; line-height: 1.6; box-sizing: border-box;
            }
            .form-textarea:focus {
                outline: none; background: rgba(255,255,255,.95);
                border-color: rgba(56,189,248,.7); box-shadow: 0 0 0 3px rgba(56,189,248,.07);
            }
            .form-textarea::placeholder { color: rgba(125,211,252,.6); }

            /* ── Activity checkbox ── */
            .activity-option { position: relative; cursor: pointer; }
            .activity-inner {
                display: flex; align-items: center; gap: 9px;
                padding: 10px 13px; border-radius: 12px; border: 1.5px solid rgba(186,230,253,.55);
                transition: all .15s; font-size: .8rem; font-weight: 600;
                background: rgba(255,255,255,.6); color: #0c4a6e;
            }
            .activity-inner:hover { border-color: rgba(56,189,248,.45); background: rgba(224,242,254,.45); }

            .rule-pill {
                display: flex; align-items: center; justify-content: space-between;
                padding: 9px 14px; border-radius: 11px; font-size: .78rem; font-weight: 500;
            }

            /* ── Submit / Cancel ── */
            .submit-btn {
                flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
                padding: 13px 20px; border-radius: 14px; font-size: .875rem; font-weight: 800;
                color: white; border: none; cursor: pointer;
                background: linear-gradient(135deg, #34d399, #059669);
                box-shadow: 0 5px 18px rgba(5,150,105,.28);
                transition: all .2s; font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 9px 26px rgba(5,150,105,.35); }
            .submit-btn:active { transform: translateY(0); }

            .cancel-btn {
                display: inline-flex; align-items: center; justify-content: center;
                padding: 13px 22px; border-radius: 14px; font-size: .875rem; font-weight: 700;
                color: #0284c7; text-decoration: none;
                background: rgba(224,242,254,.75); border: 1.5px solid rgba(186,230,253,.6); transition: all .2s;
            }
            .cancel-btn:hover { background: rgba(255,255,255,.95); }

            /* ═══════════════════════════════════════════════
               MEDIA CAPTURE — HEADER
            ═══════════════════════════════════════════════ */
            .mc-section-head {
                padding: 13px 20px; border-bottom: 1px solid rgba(186,230,253,.25);
                display: flex; align-items: center; gap: 10px;
            }
            .mc-section-icon {
                width: 30px; height: 30px; border-radius: 9px;
                background: linear-gradient(135deg, rgba(224,242,254,.95), rgba(186,230,253,.65));
                display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            }
            .mc-section-title { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .13em; color: #0284c7; }
            .mc-section-sub   { font-size: .66rem; font-weight: 500; color: #7dd3fc; margin-top: 2px; }
            .mc-required { color: #f87171; }
            .mc-body { padding: 16px 18px; display: flex; flex-direction: column; gap: 12px; }

            /* ── Type tabs ── */
            .mc-type-tabs {
                display: flex; gap: 4px; padding: 4px;
                background: rgba(240,249,255,.65); border-radius: 12px; border: 1.5px solid rgba(186,230,253,.45);
            }
            .mc-type-tab {
                flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
                padding: 8px 10px; border-radius: 9px; cursor: pointer;
                font-size: .77rem; font-weight: 700; border: none; background: transparent;
                color: #93c5fd; transition: all .18s; font-family: inherit;
            }
            .mc-type-tab:hover:not(.mc-type-tab--active) { color: #38bdf8; background: rgba(255,255,255,.4); }
            .mc-type-tab--active { background: white; color: #0284c7; box-shadow: 0 1px 10px rgba(14,165,233,.12), 0 1px 3px rgba(0,0,0,.05); }

            /* ── Source tabs ── */
            .mc-source-tabs { display: flex; gap: 7px; flex-wrap: wrap; }
            .mc-source-tab {
                display: inline-flex; align-items: center; gap: 6px;
                padding: 8px 16px; border-radius: 10px; font-size: .77rem; font-weight: 700;
                cursor: pointer; border: 1.5px solid rgba(186,230,253,.45);
                background: rgba(255,255,255,.45); color: #7dd3fc; transition: all .2s; font-family: inherit;
            }
            .mc-source-tab:hover:not(.mc-source-tab--active) { background: rgba(255,255,255,.7); border-color: rgba(56,189,248,.35); color: #38bdf8; }
            .mc-source-tab--active { background: rgba(15,23,42,.88); color: white; border-color: rgba(56,189,248,.4); box-shadow: 0 2px 14px rgba(0,0,0,.16); }

            /* ── Drop zone ── */
            .mc-dropzone {
                display: block; cursor: pointer; border-radius: 16px;
                border: 2px dashed rgba(147,197,253,.5); background: rgba(240,249,255,.3); transition: all .2s;
            }
            .mc-dropzone:hover, .mc-dropzone--over { border-color: rgba(56,189,248,.65); background: rgba(224,242,254,.45); }
            .mc-dropzone--video { border-color: rgba(196,181,253,.5); background: rgba(245,243,255,.3); }
            .mc-dropzone--video:hover { border-color: rgba(167,139,250,.65); background: rgba(237,233,254,.45); }

            .mc-dropzone-inner {
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                min-height: 130px; padding: 20px 16px; text-align: center; gap: 7px;
            }
            .mc-dz-icon-wrap {
                width: 48px; height: 48px; border-radius: 14px;
                display: flex; align-items: center; justify-content: center; margin-bottom: 2px; transition: transform .2s;
            }
            .mc-dropzone:hover .mc-dz-icon-wrap { transform: scale(1.06) translateY(-2px); }
            .mc-dz-icon-wrap--photo { background: linear-gradient(135deg, rgba(224,242,254,.9), rgba(186,230,253,.65)); box-shadow: 0 3px 14px rgba(14,165,233,.12); }
            .mc-dz-icon-wrap--video { background: linear-gradient(135deg, rgba(237,233,254,.9), rgba(221,214,254,.65)); box-shadow: 0 3px 14px rgba(139,92,246,.12); }
            .mc-dz-title { font-size: .84rem; font-weight: 700; color: #0369a1; margin: 0; }
            .mc-dz-sub   { font-size: .7rem; color: #7dd3fc; font-weight: 500; margin: 0; }
            .mc-dz-btn {
                display: inline-flex; align-items: center; gap: 5px; margin-top: 4px;
                padding: 7px 16px; border-radius: 9px; font-size: .74rem; font-weight: 700;
                background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white;
                box-shadow: 0 2px 10px rgba(14,165,233,.25); transition: all .2s;
            }
            .mc-dropzone:hover .mc-dz-btn { box-shadow: 0 4px 16px rgba(14,165,233,.38); transform: translateY(-1px); }
            .mc-dz-btn--video { background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 2px 10px rgba(139,92,246,.25); }

            /* ── Video selected ── */
            .mc-video-selected {
                display: flex; align-items: center; gap: 12px; padding: 13px 15px; border-radius: 14px;
                background: linear-gradient(135deg, rgba(237,233,254,.8), rgba(245,243,255,.6));
                border: 1.5px solid rgba(196,181,253,.45);
            }
            .mc-video-icon {
                width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
                background: rgba(139,92,246,.1); display: flex; align-items: center; justify-content: center;
                border: 1.5px solid rgba(196,181,253,.4);
            }
            .mc-video-info { flex: 1; min-width: 0; }
            .mc-video-name { font-size: .79rem; font-weight: 700; color: #4c1d95; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0; }
            .mc-video-status { display: flex; align-items: center; gap: 5px; font-size: .69rem; color: #7c3aed; font-weight: 600; margin-top: 3px; }
            .mc-video-status-dot { width: 5px; height: 5px; border-radius: 50%; background: #10b981; box-shadow: 0 0 5px rgba(16,185,129,.5); }
            .mc-video-remove {
                width: 30px; height: 30px; border-radius: 9px; flex-shrink: 0; cursor: pointer;
                border: 1.5px solid rgba(196,181,253,.5); background: rgba(255,255,255,.6);
                display: flex; align-items: center; justify-content: center; color: #7c3aed; transition: all .16s;
            }
            .mc-video-remove:hover { background: rgba(239,68,68,.08); border-color: rgba(239,68,68,.3); color: #ef4444; }

            /* ═══════════════════════════════════════════════
               PREVIEW FOTO/VIDEO — RESPONSIF IKUTI RASIO FILE
               Tidak ada max-height → tinggi mengikuti dimensi asli
            ═══════════════════════════════════════════════ */
            .mc-preview {
                position: relative; border-radius: 16px; overflow: hidden;
                border: 2px solid rgba(52,211,153,.3); box-shadow: 0 6px 24px rgba(16,185,129,.09);
            }

            /* Gambar: lebar 100%, tinggi mengikuti rasio asli file — tidak di-crop */
            .mc-preview-img {
                width: 100%;
                height: auto;        /* ← kunci: ikuti rasio asli */
                display: block;
                object-fit: contain;
                background: #f0f9ff;
            }

            /* Video: sama — lebar penuh, tinggi proporsional, max 480px agar tidak terlalu panjang */
            .mc-preview-video {
                width: 100%; height: auto; display: block;
                background: #000; max-height: 480px;
            }

            .mc-preview-badge {
                position: absolute; top: 10px; left: 10px; z-index: 2;
                display: inline-flex; align-items: center; gap: 4px;
                padding: 4px 10px; border-radius: 8px;
                font-size: .65rem; font-weight: 800; color: white;
                letter-spacing: .07em; text-transform: uppercase; backdrop-filter: blur(8px);
            }
            .mc-preview-badge--green { background: rgba(16,185,129,.88); }
            .mc-preview-badge--red   { background: rgba(239,68,68,.88); }

            .mc-preview-retake {
                position: absolute; top: 10px; right: 10px; z-index: 2;
                display: inline-flex; align-items: center; gap: 5px; padding: 6px 13px;
                border-radius: 9px; border: none; cursor: pointer;
                background: rgba(0,0,0,.52); backdrop-filter: blur(8px);
                font-size: .71rem; font-weight: 700; color: white; transition: all .16s;
            }
            .mc-preview-retake:hover { background: rgba(0,0,0,.72); }

            .mc-preview-footer {
                display: flex; align-items: center; gap: 9px; padding: 10px 14px;
                background: rgba(255,255,255,.92); backdrop-filter: blur(10px);
                border-top: 1px solid rgba(186,230,253,.2);
            }
            .mc-preview-footer-icon { width: 27px; height: 27px; border-radius: 8px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
            .mc-preview-footer-icon--blue { background: rgba(14,165,233,.08); }
            .mc-preview-footer-icon--red  { background: rgba(239,68,68,.08); }
            .mc-preview-name     { font-size: .74rem; font-weight: 700; color: #0369a1; margin: 0; flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .mc-preview-duration { font-size: .66rem; color: #7dd3fc; font-weight: 500; margin: 0; }

            /* Dimensi info — muncul di footer kalau ada data dimensi */
            .mc-preview-dim {
                font-size: .64rem; font-weight: 600; color: #7dd3fc; flex-shrink: 0;
                background: rgba(240,249,255,.8); border: 1px solid rgba(186,230,253,.4);
                padding: 3px 8px; border-radius: 6px; white-space: nowrap;
            }

            /* ── Fallback ── */
            .mc-fallback {
                display: flex; align-items: center; gap: 9px; padding: 13px 16px; border-radius: 13px;
                background: rgba(236,253,245,.75); border: 1.5px solid rgba(167,243,208,.5);
            }
            .mc-fallback-name { font-size: .79rem; font-weight: 700; color: #065f46; flex: 1; margin: 0; }
            .mc-fallback-redo { font-size: .71rem; font-weight: 700; color: #0284c7; background: none; border: none; cursor: pointer; }

            /* ═══════════════════════════════════════════════
               CAMERA — KOTAK PERSEGI (aspect-ratio: 1/1)
               Centered, max-width agar tidak terlalu besar
            ═══════════════════════════════════════════════ */
            .mc-cam-wrapper {
                position: relative; width: 100%;
                border-radius: 18px; overflow: hidden;
                background: #060d1a;
                border: 1.5px solid rgba(56,189,248,.16);
                box-shadow: 0 10px 40px rgba(0,0,0,.30), 0 0 0 1px rgba(56,189,248,.06);

                aspect-ratio: 1 / 1;   /* ← PERSEGI */

                /* Centered dengan max-width */
                max-width: 400px;
                margin-left: auto;
                margin-right: auto;
            }

            @media (min-width: 600px)  { .mc-cam-wrapper { max-width: 360px; } }
            @media (min-width: 900px)  { .mc-cam-wrapper { max-width: 320px; } }

            .mc-cam-video { width: 100%; height: 100%; object-fit: cover; display: block; background: #060d1a; }

            /* Grid */
            .mc-cam-grid { position: absolute; inset: 0; pointer-events: none; }
            .mc-cam-grid-line { position: absolute; background: rgba(255,255,255,.05); }

            /* Corners */
            .mc-cam-overlay { position: absolute; inset: 0; pointer-events: none; }
            .mc-corner { position: absolute; width: 18px; height: 18px; border-color: rgba(56,189,248,.88); border-style: solid; animation: cornerGlow 3s ease infinite; }
            .mc-corner--tl { top:10px; left:10px;   border-width:2px 0 0 2px; border-radius:4px 0 0 0; }
            .mc-corner--tr { top:10px; right:10px;  border-width:2px 2px 0 0; border-radius:0 4px 0 0; }
            .mc-corner--bl { bottom:60px; left:10px;  border-width:0 0 2px 2px; border-radius:0 0 0 4px; }
            .mc-corner--br { bottom:60px; right:10px; border-width:0 2px 2px 0; border-radius:0 0 4px 0; }

            /* Scan */
            .mc-scan-line {
                position: absolute; left: 12px; right: 12px; height: 1.5px;
                background: linear-gradient(90deg, transparent, rgba(56,189,248,.85) 30%, rgba(56,189,248,.85) 70%, transparent);
                filter: drop-shadow(0 0 4px rgba(56,189,248,.55));
                animation: scanLine 2.8s cubic-bezier(.4,0,.6,1) infinite;
            }

            /* Flash */
            .mc-cam-flash { position: absolute; inset: 0; background: white; pointer-events: none; opacity: 0; z-index: 20; }
            .mc-cam-flash--active { animation: shutter .32s ease forwards; }

            /* Top bar */
            .mc-cam-topbar {
                position: absolute; top: 0; left: 0; right: 0; z-index: 10;
                display: flex; align-items: center; justify-content: space-between; padding: 10px 12px;
                background: linear-gradient(to bottom, rgba(0,0,0,.55) 0%, transparent 100%);
            }
            .mc-cam-mode-badge {
                display: inline-flex; align-items: center; gap: 5px; padding: 4px 9px; border-radius: 7px;
                background: rgba(0,0,0,.4); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,.1);
                font-size: .62rem; font-weight: 800; color: white; letter-spacing: .09em; text-transform: uppercase;
            }
            .mc-cam-mode-badge--rec { background: rgba(239,68,68,.36); border-color: rgba(239,68,68,.42); }
            .mc-cam-rec-dot { width: 6px; height: 6px; border-radius: 50%; background: #ef4444; animation: recordPulse 1.1s ease infinite; box-shadow: 0 0 7px rgba(239,68,68,.7); }
            .mc-cam-hint { display: flex; align-items: center; gap: 4px; font-size: .6rem; font-weight: 600; color: rgba(255,255,255,.55); }

            /* Bottom controls */
            .mc-cam-controls {
                position: absolute; bottom: 0; left: 0; right: 0; z-index: 10;
                display: flex; align-items: center; justify-content: center; gap: 20px;
                padding: 10px 14px 15px;
                background: linear-gradient(to top, rgba(0,0,0,.65) 0%, transparent 100%);
            }

            /* Side buttons */
            .mc-cam-btn {
                width: 36px; height: 36px; border-radius: 50%;
                border: 1.5px solid rgba(255,255,255,.2); background: rgba(255,255,255,.1); backdrop-filter: blur(10px);
                cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px;
                color: white; transition: all .18s;
            }
            .mc-cam-btn:hover { background: rgba(255,255,255,.2); transform: scale(1.06); }
            .mc-cam-btn:active { transform: scale(.94); }
            .mc-cam-btn-label { font-size: .46rem; font-weight: 700; color: rgba(255,255,255,.55); text-transform: uppercase; letter-spacing: .04em; }

            /* Shutter */
            .mc-shutter-btn {
                width: 54px; height: 54px; border-radius: 50%;
                border: none; cursor: pointer; background: transparent;
                position: relative; display: flex; align-items: center; justify-content: center; transition: all .14s; flex-shrink: 0;
            }
            .mc-shutter-ring {
                position: absolute; inset: 0; border-radius: 50%;
                border: 2.5px solid rgba(255,255,255,.85);
                box-shadow: 0 0 0 2px rgba(255,255,255,.16), 0 4px 18px rgba(0,0,0,.38); transition: all .2s;
            }
            .mc-shutter-ring--rec { border-color: rgba(239,68,68,.85); box-shadow: 0 0 0 2px rgba(239,68,68,.2), 0 4px 22px rgba(239,68,68,.42); animation: recordPulse 1.1s ease infinite; }
            .mc-shutter-inner {
                width: 42px; height: 42px; border-radius: 50%;
                background: white; display: flex; align-items: center; justify-content: center;
                position: relative; z-index: 1; transition: all .14s;
            }
            .mc-shutter-btn--rec .mc-shutter-inner { background: #ef4444; box-shadow: 0 0 18px rgba(239,68,68,.5); }
            .mc-shutter-btn:hover .mc-shutter-inner { transform: scale(.94); }
            .mc-shutter-btn:active .mc-shutter-inner { transform: scale(.87); }

            /* Idle — same square shape as camera */
            .mc-cam-idle {
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                aspect-ratio: 1 / 1; max-width: 400px; margin: 0 auto;
                border-radius: 18px; background: rgba(6,13,26,.03);
                border: 2px dashed rgba(56,189,248,.2); gap: 9px;
            }
            @media (min-width: 600px) { .mc-cam-idle { max-width: 360px; } }
            @media (min-width: 900px) { .mc-cam-idle { max-width: 320px; } }

            .mc-cam-idle-spinner-wrap { position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
            .mc-cam-idle-spinner { position: absolute; inset: 0; border-radius: 50%; border: 2px solid transparent; border-top-color: rgba(56,189,248,.6); border-right-color: rgba(56,189,248,.15); animation: spinRing 1.2s linear infinite; }
            .mc-cam-idle-icon { width: 36px; height: 36px; border-radius: 10px; background: rgba(240,249,255,.72); display: flex; align-items: center; justify-content: center; }
            .mc-cam-idle-text { font-size: .81rem; font-weight: 700; color: #0369a1; margin: 0; }
            .mc-cam-idle-sub  { font-size: .67rem; font-weight: 500; color: #7dd3fc; margin: 0; }

            /* Hint */
            .mc-hint { display: flex; justify-content: center; margin-top: -4px; }
            .mc-hint-inner {
                display: inline-flex; align-items: center; gap: 5px; font-size: .69rem; font-weight: 600; color: #93c5fd;
                padding: 4px 12px; border-radius: 7px; background: rgba(240,249,255,.5); border: 1px solid rgba(186,230,253,.35);
            }
        </style>

        <div class="flex items-center gap-3 header-in">
            <a href="{{ request('from') === 'dashboard' ? route('dashboard.student') : route('student.habits.today') }}"
               style="width:38px;height:38px;border-radius:13px;display:flex;align-items:center;justify-content:center;background:rgba(224,242,254,.75);border:1.5px solid rgba(186,230,253,.6);color:#0284c7;transition:all .18s;flex-shrink:0;text-decoration:none"
               onmouseover="this.style.background='rgba(255,255,255,.95)'"
               onmouseout="this.style.background='rgba(224,242,254,.75)'">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:#38bdf8;margin-bottom:3px">Siswa &middot; Habit</p>
                <h1 style="font-size:1.38rem;font-weight:900;color:#0c4a6e;letter-spacing:-.03em;line-height:1.2">
                    {{ $habit->is_multi_select ? 'Tambah ' . $habit->name : 'Submit ' . ($habitItem?->name ?? $habit->name) }}
                </h1>
                <p style="font-size:.78rem;font-weight:500;color:#38bdf8;margin-top:2px">
                    @if($habit->is_multi_select)
                        Pilih <strong style="color:#0369a1">minimal {{ $habit->max_select ?? 3 }} jenis</strong> kegiatan yang sudah dilakukan
                    @else
                        Unggah bukti kebiasaan kamu hari ini
                    @endif
                </p>
            </div>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            <div style="width:100%;margin:0 auto">

                {{-- ══ TIPE B: Multi-Select ══ --}}
                @if($habit->is_multi_select)
                <form method="POST" action="{{ route('student.habits.submit.store', ['habit' => $habit->id] + request()->only('from')) }}"
                      enctype="multipart/form-data"
                      x-data="multiSelectForm({{ $habit->max_select ?? 3 }})"
                      style="display:flex;flex-direction:column;gap:14px">
                    @csrf

                    @if(isset($multiSelectRules) && $multiSelectRules->isNotEmpty())
                    <div class="form-section" style="animation:floatUp .4s ease .05s both">
                        <div class="section-head">
                            <div class="section-icon">
                                <svg width="12" height="12" fill="none" stroke="#0284c7" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <span class="section-title">Info Poin</span>
                        </div>
                        <div class="section-body" style="display:flex;flex-wrap:wrap;gap:7px">
                            @foreach($multiSelectRules as $rule)
                            <div style="display:inline-flex;align-items:center;gap:5px;padding:6px 13px;border-radius:11px;font-size:.79rem;background:rgba(236,253,245,.75);border:1.5px solid rgba(167,243,208,.5)">
                                <span style="font-weight:900;color:#059669">{{ $rule->point }}</span>
                                <span style="color:#7dd3fc">poin</span>
                                <span style="color:rgba(147,197,253,.5)">&middot;</span>
                                <span style="font-weight:700;color:#0369a1">&ge;{{ $rule->min_items_selected }} kegiatan</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="form-section" style="animation:floatUp .4s ease .1s both">
                        <div class="section-head" style="justify-content:space-between">
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="section-icon">
                                    <svg width="12" height="12" fill="none" stroke="#0284c7" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <span class="section-title">Jenis Kegiatan</span>
                                    <p style="font-size:.65rem;font-weight:500;color:#7dd3fc;margin-top:2px">Pilih {{ $habit->max_select ?? 3 }} kegiatan</p>
                                </div>
                            </div>
                            <span style="font-size:.92rem;font-weight:900" :style="selectedCount === maxSelect ? 'color:#059669' : 'color:#93c5fd'">
                                <span x-text="selectedCount"></span>/<span x-text="maxSelect"></span>
                            </span>
                        </div>
                        <div class="section-body" style="display:grid;grid-template-columns:1fr 1fr;gap:7px">
                            @foreach($activityOptions as $option)
                            <label class="activity-option" :class="{'opacity-50 cursor-not-allowed': !isSelected({{ $option->id }}) && selectedCount >= maxSelect}">
                                <input type="checkbox" name="selected_activities[]" value="{{ $option->id }}"
                                       class="sr-only" x-on:change="toggle({{ $option->id }}, $event)"
                                       :disabled="!isSelected({{ $option->id }}) && selectedCount >= maxSelect">
                                <div class="activity-inner" :style="isSelected({{ $option->id }}) ? 'background:rgba(236,253,245,.85);border-color:rgba(52,211,153,.5);color:#065f46' : ''">
                                    <div style="width:17px;height:17px;border-radius:5px;flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .14s"
                                         :style="isSelected({{ $option->id }}) ? 'background:linear-gradient(135deg,#34d399,#10b981)' : 'border:2px solid rgba(147,197,253,.65)'">
                                        <svg x-show="isSelected({{ $option->id }})" width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span>{{ $option->name }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <div style="padding:0 20px 16px">
                            <div x-show="selectedCount === maxSelect" style="display:flex;align-items:center;gap:5px;font-size:.77rem;font-weight:700;color:#059669">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Sudah memilih <span x-text="selectedCount"></span> kegiatan
                            </div>
                            <div x-show="selectedCount < maxSelect" style="font-size:.77rem;font-weight:500;color:#7dd3fc">
                                Dipilih: <span x-text="selectedCount"></span> dari <span x-text="maxSelect"></span>
                            </div>
                        </div>
                        @error('selected_activities')
                        <p style="padding:0 20px 16px;font-size:.79rem;color:#be123c;font-weight:600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-section" style="animation:floatUp .4s ease .15s both">
                        <div class="section-head">
                            <div class="section-icon">
                                <svg width="12" height="12" fill="none" stroke="#0284c7" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <span class="section-title">Deskripsi Aktivitas <span style="color:#f87171">*</span></span>
                                <p style="font-size:.65rem;font-weight:500;color:#7dd3fc;margin-top:2px">Tuliskan pengalaman atau pelajaran yang didapat</p>
                            </div>
                        </div>
                        <div class="section-body">
                            <textarea name="description" rows="4" placeholder="Tuliskan deskripsi aktivitas selama mengikuti kegiatan ini..." class="form-textarea">{{ old('description') }}</textarea>
                            <p style="font-size:.69rem;font-weight:500;color:#7dd3fc;margin-top:5px">Minimal 10 karakter</p>
                            @error('description')
                            <p style="font-size:.77rem;color:#be123c;font-weight:600;margin-top:4px">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section" style="animation:floatUp .4s ease .2s both" x-data="mediaCapture('proof_multi')">
                        @include('student.partials._media-capture-section')
                        @error('proof')
                        <p style="padding:0 20px 16px;font-size:.77rem;color:#be123c;font-weight:600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display:flex;gap:10px;animation:floatUp .4s ease .25s both">
                        <button type="submit" class="submit-btn">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Kirim Bukti
                        </button>
                        <a href="{{ request('from') === 'dashboard' ? route('dashboard.student') : route('student.habits.today') }}" class="cancel-btn">Batal</a>
                    </div>
                </form>

                @else
                {{-- ══ TIPE A: Single Item ══ --}}
                <form method="POST" action="{{ route('student.habits.submit.store', ['habit' => $habit->id] + request()->only('from')) }}"
                      enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:14px">
                    @csrf
                    @if($habitItem)
                    <input type="hidden" name="habit_item_id" value="{{ $habitItem->id }}">
                    @endif

                    @if($currentRule)
                    <div style="padding:16px 20px;border-radius:20px;backdrop-filter:blur(16px);background:rgba(236,253,245,.88);border:1.5px solid rgba(167,243,208,.5);animation:floatUp .4s ease .05s both;box-shadow:0 4px 20px rgba(16,185,129,.07)">
                        <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.13em;color:#059669;margin-bottom:6px">Rule Aktif Sekarang</p>
                        <div style="display:flex;align-items:center;justify-content:space-between">
                            <span style="font-size:.88rem;font-weight:800;color:#065f46">{{ $currentRule->name }}</span>
                            <span style="font-size:1.28rem;font-weight:900;color:#059669">+{{ $currentRule->point }} poin</span>
                        </div>
                        @if($currentRule->start_time && $currentRule->end_time)
                        <p style="font-size:.71rem;color:#34d399;margin-top:4px;font-weight:600;display:flex;align-items:center;gap:4px">
                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                            {{ \Carbon\Carbon::parse($currentRule->start_time)->format('H:i') }} &ndash; {{ \Carbon\Carbon::parse($currentRule->end_time)->format('H:i') }}
                        </p>
                        @endif
                    </div>
                    @elseif($rules->isNotEmpty())
                    <div class="form-section" style="animation:floatUp .4s ease .05s both">
                        <div class="section-head">
                            <div class="section-icon">
                                <svg width="12" height="12" fill="none" stroke="#0284c7" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <span class="section-title">Ketentuan Poin</span>
                        </div>
                        <div class="section-body" style="display:flex;flex-direction:column;gap:6px">
                            @foreach($rules as $rule)
                            <div class="rule-pill" style="background:rgba(240,249,255,.65);border:1px solid rgba(186,230,253,.4)">
                                <span style="font-size:.79rem;font-weight:600;color:#0369a1">
                                    {{ $rule->name }}
                                    @if($rule->start_time && $rule->end_time)
                                    <span style="color:#7dd3fc;font-weight:500">({{ \Carbon\Carbon::parse($rule->start_time)->format('H:i') }}&ndash;{{ \Carbon\Carbon::parse($rule->end_time)->format('H:i') }})</span>
                                    @endif
                                </span>
                                <span style="font-size:.86rem;font-weight:900;color:#059669">+{{ $rule->point }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @php $isTimeBased = $rules->where('rule_type','time')->isNotEmpty(); @endphp
                    <div class="form-section" style="animation:floatUp .4s ease .1s both">
                        <div class="section-head">
                            <div class="section-icon">
                                <svg width="12" height="12" fill="none" stroke="#0284c7" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <span class="section-title">
                                    Deskripsi Aktivitas
                                    @if(!$isTimeBased)<span style="color:#f87171"> *</span>
                                    @else<span style="font-size:.62rem;font-weight:500;color:#7dd3fc;text-transform:none;letter-spacing:0"> (opsional)</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="section-body">
                            <textarea name="description" rows="3"
                                      placeholder="{{ $isTimeBased ? 'Ceritakan pengalamanmu (opsional)...' : 'Deskripsikan kegiatan yang kamu lakukan...' }}"
                                      class="form-textarea">{{ old('description') }}</textarea>
                            @error('description')
                            <p style="font-size:.77rem;color:#be123c;font-weight:600;margin-top:4px">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section" style="animation:floatUp .4s ease .15s both" x-data="mediaCapture('proof_single')">
                        @include('student.partials._media-capture-section')
                        @error('proof')
                        <p style="padding:0 20px 16px;font-size:.77rem;color:#be123c;font-weight:600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display:flex;gap:10px;animation:floatUp .4s ease .2s both">
                        <button type="submit" class="submit-btn">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Kirim Bukti
                        </button>
                        <a href="{{ request('from') === 'dashboard' ? route('dashboard.student') : route('student.habits.today') }}" class="cancel-btn">Batal</a>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>

    <script>
    function multiSelectForm(maxSelect) {
        return {
            maxSelect, selected: [],
            get selectedCount() { return this.selected.length; },
            isSelected(id) { return this.selected.includes(id); },
            toggle(id, event) {
                if (this.isSelected(id)) { this.selected = this.selected.filter(i => i !== id); }
                else { if (this.selected.length < this.maxSelect) { this.selected.push(id); } else { event.target.checked = false; } }
            }
        }
    }

    function mediaCapture(inputId) {
        return {
            inputId,
            mode: 'upload',
            mediaType: 'photo',
            captureState: 'idle',
            preview: null,
            fileName: null,
            fileDimension: null,  /* "1920×1080" — ditampilkan di footer preview */
            stream: null,
            mediaRecorder: null,
            recordedChunks: [],
            recordingTimer: null,
            recordingSeconds: 0,
            flashActive: false,
            facingMode: 'environment',

            get isRecording() { return this.captureState === 'recording'; },
            get isCaptured()  { return this.captureState === 'captured'; },

            formatTime(s) {
                const m = Math.floor(s / 60), sec = s % 60;
                return `${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
            },

            setMode(m) {
                if (m === this.mode) return;
                this.stopCamera();
                this.mode = m; this.preview = null; this.fileName = null;
                this.fileDimension = null; this.captureState = 'idle';
                this.recordedChunks = []; this.recordingSeconds = 0;
                if (m === 'camera') this.$nextTick(() => this.startCamera());
            },

            setMediaType(t) {
                if (t === this.mediaType) return;
                this.mediaType = t; this.preview = null; this.fileName = null;
                this.fileDimension = null;
                this.captureState = this.mode === 'camera' ? 'streaming' : 'idle';
                this.recordedChunks = []; this.stopRecordingTimer(); this.recordingSeconds = 0;
                if (this.mode === 'camera') this.$nextTick(() => this.startCamera());
            },

            /* ── FILE UPLOAD — baca dimensi asli gambar ── */
            onFileChange(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.fileName = file.name;
                this.fileDimension = null;

                if (this.mediaType === 'photo') {
                    const reader = new FileReader();
                    reader.onload = e => {
                        this.preview = e.target.result;
                        /* Baca dimensi natural dari gambar */
                        const img = new Image();
                        img.onload = () => { this.fileDimension = `${img.naturalWidth}×${img.naturalHeight}`; };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
                this.captureState = 'captured';
                this.attachFileToForm(file);
            },

            /* ── CAMERA ── */
            async startCamera() {
                try {
                    this.stopCamera();
                    /* Minta resolusi kotak (1:1) agar sesuai viewfinder */
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: this.facingMode, width: { ideal: 1080 }, height: { ideal: 1080 } },
                        audio: this.mediaType === 'video',
                    });
                    await this.$nextTick();
                    const video = this.$refs.cameraVideo;
                    if (video) { video.srcObject = this.stream; video.play(); }
                    this.captureState = 'streaming';
                } catch (err) {
                    console.error('Camera error:', err);
                    alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
                    this.mode = 'upload';
                }
            },

            stopCamera() {
                if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null; }
                this.stopRecordingTimer();
                if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') this.mediaRecorder.stop();
                this.mediaRecorder = null;
            },

            /* ── TAKE PHOTO — simpan dimensi ── */
            async takePhoto() {
                const video = this.$refs.cameraVideo;
                if (!video) return;
                this.flashActive = true;
                setTimeout(() => this.flashActive = false, 320);
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth; canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                canvas.toBlob(blob => {
                    if (!blob) return;
                    const file = new File([blob], `foto_${Date.now()}.jpg`, { type: 'image/jpeg' });
                    this.preview = canvas.toDataURL('image/jpeg', 0.92);
                    this.fileName = file.name;
                    this.fileDimension = `${canvas.width}×${canvas.height}`;
                    this.captureState = 'captured';
                    this.stopCamera();
                    this.attachFileToForm(file);
                }, 'image/jpeg', 0.92);
            },

            /* ── VIDEO RECORD ── */
            startRecording() {
                if (!this.stream) return;
                this.recordedChunks = []; this.recordingSeconds = 0;
                const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp9') ? 'video/webm;codecs=vp9'
                    : MediaRecorder.isTypeSupported('video/webm') ? 'video/webm' : 'video/mp4';
                this.mediaRecorder = new MediaRecorder(this.stream, { mimeType });
                this.mediaRecorder.ondataavailable = e => { if (e.data.size > 0) this.recordedChunks.push(e.data); };
                this.mediaRecorder.onstop = () => this.processRecording(mimeType);
                this.mediaRecorder.start(100);
                this.captureState = 'recording';
                this.startRecordingTimer();
            },

            stopRecording() {
                if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') this.mediaRecorder.stop();
                this.stopRecordingTimer(); this.captureState = 'captured'; this.stopCamera();
            },

            processRecording(mimeType) {
                const ext = mimeType.includes('mp4') ? 'mp4' : 'webm';
                const blob = new Blob(this.recordedChunks, { type: mimeType });
                const file = new File([blob], `video_${Date.now()}.${ext}`, { type: mimeType });
                this.fileName = file.name; this.preview = URL.createObjectURL(blob);
                this.attachFileToForm(file);
            },

            startRecordingTimer() { this.recordingTimer = setInterval(() => this.recordingSeconds++, 1000); },
            stopRecordingTimer()  { if (this.recordingTimer) { clearInterval(this.recordingTimer); this.recordingTimer = null; } },

            async flipCamera() { this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment'; await this.startCamera(); },

            retake() {
                this.preview = null; this.fileName = null; this.fileDimension = null;
                this.recordedChunks = []; this.recordingSeconds = 0;
                const inp = document.getElementById(this.inputId + '_hidden');
                if (inp) inp.value = '';
                if (this.mode === 'camera') { this.captureState = 'streaming'; this.$nextTick(() => this.startCamera()); }
                else { this.captureState = 'idle'; }
            },

            attachFileToForm(file) {
                const dt = new DataTransfer(); dt.items.add(file);
                const inp = document.getElementById(this.inputId + '_hidden');
                if (inp) inp.files = dt.files;
            },

            destroy() { this.stopCamera(); },
        }
    }
    </script>
</x-app-layout>