{{--
    Partial: student/partials/_media-capture-section.blade.php
    Requires Alpine.js component: x-data="mediaCapture('<inputId>')"
    Parent must have: mediaCapture() dari submit.blade.php
--}}

{{-- Hidden file input --}}
<input type="file" name="proof" :id="inputId + '_hidden'"
       x-ref="hiddenProofInput" class="sr-only"
       accept="image/jpg,image/jpeg,image/png,image/webp,video/mp4,video/quicktime,video/x-m4v,video/webm">

<div class="mc-section-head">
    <div class="mc-section-icon">
        <svg width="14" height="14" fill="none" stroke="#0ea5e9" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <div>
        <span class="mc-section-title">Bukti Kegiatan <span class="mc-required">*</span></span>
        <p class="mc-section-sub">Unggah foto atau video sebagai bukti aktivitas</p>
    </div>
</div>

<div class="mc-body">

    {{-- ── TYPE TABS ── --}}
    <div class="mc-type-tabs">
        <button type="button" class="mc-type-tab" :class="{'mc-type-tab--active': mediaType === 'photo'}" @click="setMediaType('photo')">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="5" width="18" height="14" rx="2"/>
                <circle cx="8.5" cy="10.5" r="1.5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
            </svg>
            <span>Foto</span>
        </button>
        <button type="button" class="mc-type-tab" :class="{'mc-type-tab--active': mediaType === 'video'}" @click="setMediaType('video')">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span>Video</span>
        </button>
    </div>

    {{-- ── SOURCE TABS ── --}}
    <div class="mc-source-tabs">
        <button type="button" class="mc-source-tab" :class="{'mc-source-tab--active': mode === 'upload'}" @click="setMode('upload')">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Galeri / File
        </button>
        <button type="button" class="mc-source-tab" :class="{'mc-source-tab--active': mode === 'camera'}" @click="setMode('camera')">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Buka Kamera
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODE: UPLOAD
    ══════════════════════════════════════════════════════ --}}
    <div x-show="mode === 'upload'" x-transition:enter.duration.200ms style="display:none">

        {{-- Foto upload --}}
        <div x-show="mediaType === 'photo'">

            {{-- Drop zone (belum ada foto) --}}
            <template x-if="!preview">
                <label class="mc-dropzone">
                    <input type="file" accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="sr-only" @change="onFileChange($event)">
                    <div class="mc-dropzone-inner"
                         @dragover.prevent="$el.closest('label').classList.add('mc-dropzone--over')"
                         @dragleave.prevent="$el.closest('label').classList.remove('mc-dropzone--over')"
                         @drop.prevent="$el.closest('label').classList.remove('mc-dropzone--over');
                                        $event.dataTransfer.files[0] && onFileChange({target:{files:$event.dataTransfer.files}})">
                        <div class="mc-dz-icon-wrap mc-dz-icon-wrap--photo">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#0ea5e9" stroke-width="1.5">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="8.5" cy="10.5" r="1.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                            </svg>
                        </div>
                        <p class="mc-dz-title">Klik atau seret foto ke sini</p>
                        <p class="mc-dz-sub">JPG, PNG, WEBP &mdash; Maks. 5 MB</p>
                        <div class="mc-dz-btn">
                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Pilih Foto
                        </div>
                    </div>
                </label>
            </template>

            {{--
                PREVIEW FOTO DARI GALERI
                • width: 100%, height: auto  → tinggi ikuti rasio asli file
                • object-fit: contain        → tidak di-crop, tampil utuh
                • Dimensi asli ditampilkan di footer (fileDimension)
            --}}
            <template x-if="preview">
                <div class="mc-preview">
                    <div class="mc-preview-badge mc-preview-badge--green">
                        <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Foto Terpilih
                    </div>
                    <button type="button" class="mc-preview-retake" @click="retake()">
                        <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Ganti
                    </button>
                    {{-- Gambar mengikuti rasio asli (height: auto) --}}
                    <img :src="preview" class="mc-preview-img" alt="Preview foto">
                    <div class="mc-preview-footer">
                        <div class="mc-preview-footer-icon mc-preview-footer-icon--blue">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="8.5" cy="10.5" r="1.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                            </svg>
                        </div>
                        <p class="mc-preview-name" x-text="fileName"></p>
                        {{-- Info dimensi asli --}}
                        <span class="mc-preview-dim" x-show="fileDimension" x-text="fileDimension"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- Video upload --}}
        <div x-show="mediaType === 'video'">
            <template x-if="!fileName">
                <label class="mc-dropzone mc-dropzone--video">
                    <input type="file" accept="video/mp4,video/quicktime,video/x-m4v,video/webm"
                           class="sr-only" @change="onFileChange($event)">
                    <div class="mc-dropzone-inner">
                        <div class="mc-dz-icon-wrap mc-dz-icon-wrap--video">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#8b5cf6" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="mc-dz-title">Klik untuk pilih video</p>
                        <p class="mc-dz-sub">MP4, MOV, WEBM &mdash; Maks. 20 MB</p>
                        <div class="mc-dz-btn mc-dz-btn--video">
                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Pilih Video
                        </div>
                    </div>
                </label>
            </template>
            <template x-if="fileName">
                <div class="mc-video-selected">
                    <div class="mc-video-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#8b5cf6" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="mc-video-info">
                        <p class="mc-video-name" x-text="fileName"></p>
                        <div class="mc-video-status">
                            <div class="mc-video-status-dot"></div>
                            Video siap dikirim
                        </div>
                    </div>
                    <button type="button" @click="retake()" class="mc-video-remove" title="Hapus">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODE: CAMERA
         Viewfinder KOTAK (1:1) — sesuai CSS .mc-cam-wrapper
    ══════════════════════════════════════════════════════ --}}
    <div x-show="mode === 'camera'" style="display:none">

        {{-- Viewfinder aktif --}}
        <div x-show="captureState === 'streaming' || captureState === 'recording'" class="mc-cam-wrapper">

            <div class="mc-cam-flash" :class="{'mc-cam-flash--active': flashActive}"></div>

            {{-- Video stream --}}
            <video x-ref="cameraVideo" class="mc-cam-video" autoplay playsinline muted></video>

            {{-- Grid --}}
            <div class="mc-cam-grid" aria-hidden="true">
                <div class="mc-cam-grid-line" style="left:33.33%;top:0;width:1px;height:100%"></div>
                <div class="mc-cam-grid-line" style="left:66.66%;top:0;width:1px;height:100%"></div>
                <div class="mc-cam-grid-line" style="top:33.33%;left:0;height:1px;width:100%"></div>
                <div class="mc-cam-grid-line" style="top:66.66%;left:0;height:1px;width:100%"></div>
            </div>

            {{-- Corner brackets + scan --}}
            <div class="mc-cam-overlay" aria-hidden="true">
                <div class="mc-corner mc-corner--tl"></div>
                <div class="mc-corner mc-corner--tr"></div>
                <div class="mc-corner mc-corner--bl"></div>
                <div class="mc-corner mc-corner--br"></div>
                <div class="mc-scan-line" x-show="captureState === 'streaming'"></div>
            </div>

            {{-- Top bar --}}
            <div class="mc-cam-topbar">
                <div class="mc-cam-mode-badge" :class="{'mc-cam-mode-badge--rec': captureState === 'recording'}">
                    <div class="mc-cam-rec-dot" x-show="captureState === 'recording'"></div>
                    <svg x-show="captureState !== 'recording' && mediaType === 'photo'" width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <circle cx="8.5" cy="10.5" r="1.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                    </svg>
                    <svg x-show="captureState !== 'recording' && mediaType === 'video'" width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="captureState === 'recording' ? 'REC · ' + formatTime(recordingSeconds) : (mediaType === 'video' ? 'VIDEO' : 'FOTO')"></span>
                </div>
                <div class="mc-cam-hint">
                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4l2 2"/>
                    </svg>
                    <span x-text="mediaType === 'photo' ? 'Tap untuk foto' : (captureState === 'recording' ? 'Tap untuk stop' : 'Tap untuk rekam')"></span>
                </div>
            </div>

            {{-- Controls --}}
            <div class="mc-cam-controls">
                {{-- Flip --}}
                <button type="button" class="mc-cam-btn" @click="flipCamera()" title="Balik kamera">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="mc-cam-btn-label">Balik</span>
                </button>

                {{-- Shutter: foto --}}
                <template x-if="mediaType === 'photo'">
                    <button type="button" class="mc-shutter-btn" @click="takePhoto()" title="Ambil foto">
                        <div class="mc-shutter-ring"></div>
                        <div class="mc-shutter-inner">
                            <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="#0369a1" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </button>
                </template>

                {{-- Shutter: video --}}
                <template x-if="mediaType === 'video'">
                    <button type="button" class="mc-shutter-btn" :class="{'mc-shutter-btn--rec': isRecording}"
                            @click="isRecording ? stopRecording() : startRecording()" title="Rekam / Stop">
                        <div class="mc-shutter-ring" :class="{'mc-shutter-ring--rec': isRecording}"></div>
                        <div class="mc-shutter-inner">
                            <template x-if="!isRecording">
                                <svg width="16" height="16" fill="#ef4444" viewBox="0 0 24 24"><circle cx="12" cy="12" r="7"/></svg>
                            </template>
                            <template x-if="isRecording">
                                <svg width="14" height="14" fill="white" viewBox="0 0 24 24"><rect x="5" y="5" width="14" height="14" rx="3"/></svg>
                            </template>
                        </div>
                    </button>
                </template>

                {{-- Tutup --}}
                <button type="button" class="mc-cam-btn" @click="setMode('upload')" title="Tutup kamera">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="mc-cam-btn-label">Tutup</span>
                </button>
            </div>
        </div>

        {{-- HASIL CAPTURE --}}
        <div x-show="captureState === 'captured'" style="display:none">

            {{-- Foto dari kamera — rasio ikut hasil foto (width:100% height:auto) --}}
            <template x-if="preview && mediaType === 'photo'">
                <div class="mc-preview">
                    <div class="mc-preview-badge mc-preview-badge--green">
                        <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Foto Diambil
                    </div>
                    <button type="button" class="mc-preview-retake" @click="retake()">
                        <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Foto Ulang
                    </button>
                    <img :src="preview" class="mc-preview-img" alt="Foto yang diambil">
                    <div class="mc-preview-footer">
                        <div class="mc-preview-footer-icon mc-preview-footer-icon--blue">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2">
                                <rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10.5" r="1.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                            </svg>
                        </div>
                        <p class="mc-preview-name" x-text="fileName"></p>
                        <span class="mc-preview-dim" x-show="fileDimension" x-text="fileDimension"></span>
                    </div>
                </div>
            </template>

            {{-- Video dari kamera — rasio ikut file (height:auto, max-height:480px) --}}
            <template x-if="preview && mediaType === 'video'">
                <div class="mc-preview">
                    <div class="mc-preview-badge mc-preview-badge--red">
                        <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Video Direkam
                    </div>
                    <button type="button" class="mc-preview-retake" @click="retake()">
                        <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Rekam Ulang
                    </button>
                    <video :src="preview" controls class="mc-preview-video"></video>
                    <div class="mc-preview-footer">
                        <div class="mc-preview-footer-icon mc-preview-footer-icon--red">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div style="flex:1;min-width:0">
                            <p class="mc-preview-name" x-text="fileName"></p>
                            <p class="mc-preview-duration" x-text="'Durasi: ' + formatTime(recordingSeconds)"></p>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Fallback --}}
            <template x-if="!preview && fileName">
                <div class="mc-fallback">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="mc-fallback-name" x-text="fileName"></span>
                    <button type="button" @click="retake()" class="mc-fallback-redo">Ulang</button>
                </div>
            </template>
        </div>

        {{-- Idle placeholder — kotak sama dengan viewfinder --}}
        <div x-show="captureState === 'idle'" style="display:none">
            <div class="mc-cam-idle">
                <div class="mc-cam-idle-spinner-wrap">
                    <div class="mc-cam-idle-spinner"></div>
                    <div class="mc-cam-idle-icon">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#38bdf8" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mc-cam-idle-text">Memuat kamera...</p>
                <p class="mc-cam-idle-sub">Pastikan izin kamera sudah diberikan</p>
            </div>
        </div>
    </div>

    {{-- Hint footer --}}
    <div class="mc-hint" x-show="!isCaptured">
        <template x-if="mediaType === 'photo'">
            <div class="mc-hint-inner">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                    <circle cx="8.5" cy="10.5" r="1.5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                </svg>
                Foto maks. 5 MB &nbsp;&middot;&nbsp; JPG, PNG, WEBP
            </div>
        </template>
        <template x-if="mediaType === 'video'">
            <div class="mc-hint-inner">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Video maks. 20 MB &nbsp;&middot;&nbsp; MP4, MOV, WEBM
            </div>
        </template>
    </div>

</div>