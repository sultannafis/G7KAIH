@props(['student' => null, 'school' => null, 'is_preview' => false, 'showMajor' => true])

@php
    $major = $student && $student->student ? $student->student->major : null;

    // Ukuran logo dalam % dari lebar container header pill (170px)
    // qr_logo1_size disimpan sebagai % dari LEBAR KARTU (320px)
    // Kita konversi ke % dari pill header (170px) → ratio 320/170 ≈ 1.882
    $cardWidth   = 320;
    $pillWidth   = 170;
    $ratio        = $cardWidth / $pillWidth; // ~1.882

    $logo1SizeRaw = $school->qr_logo1_size ?? 15; // % dari lebar kartu
    $logo2SizeRaw = $school->qr_logo2_size ?? 15;

    // Konversi ke % dari pill header
    $logo1SizePct = round($logo1SizeRaw * $ratio, 2); // % dari pill
    $logo2SizePct = round($logo2SizeRaw * $ratio, 2);

    // Clamp agar tidak overflow pill
    $logo1SizePct = min($logo1SizePct, 90);
    $logo2SizePct = min($logo2SizePct, 90);
@endphp

<div id="qr-card-{{ $cardId ?? ($student->id ?? 'admin') }}" class="qr-card-root"
     style="
        width: 320px;
        height: 480px;
        background: #ffffff;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
     ">

    {{-- Background Area (Inner top rounded) --}}
    <div style="
        position: absolute;
        top: 10px; left: 10px; right: 10px; height: 50%;
        background-color: #0f172a;
        border-radius: 10px;
        overflow: hidden;
    ">
        <img src="{{ $school->qr_bg_url }}"
             alt="Background"
             style="width: 100%; height: 100%; object-fit: cover;"
             crossorigin="anonymous">
    </div>

    {{-- Bottom Area Pattern --}}
    <div style="
        position: absolute;
        bottom: 0; left: 0; right: 0; height: 50%;
        background: #ffffff;
        z-index: 1;
    ">
        <div style="
            position: absolute; inset: 0; 
            background: repeating-linear-gradient(135deg, transparent, transparent 30px, rgba(226, 232, 240, 0.3) 30px, rgba(226, 232, 240, 0.3) 60px);
        "></div>
    </div>

    {{-- Top Logos (White Pill Container) --}}
    <div class="qr-card-header"
         style="
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            width: {{ $pillWidth }}px;
            height: 52px;
            border-radius: 0 0 16px 16px;
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
         ">

         {{-- Logo 1: posisi & ukuran berdasarkan % dari pill --}}
         @if($school->qr_show_logo1)
            <img src="{{ $school->qr_logo1_url }}"
                 alt="Logo"
                 class="qr-logo-draggable" data-logo="1"
                 style="
                    position: absolute;
                    width: {{ $logo1SizePct }}%;
                    height: auto;
                    max-height: 90%;
                    object-fit: contain;
                    top: {{ $school->qr_logo1_y ?? 50 }}%;
                    left: {{ $school->qr_logo1_x ?? 25 }}%;
                    transform: translate(-50%, -50%);
                    cursor: default;
                 "
                 crossorigin="anonymous" draggable="false">
         @endif

         {{-- Logo 2 --}}
         @if($school->qr_show_logo2)
            <img src="{{ $school->qr_logo2_url }}"
                 alt="Logo 2"
                 class="qr-logo-draggable" data-logo="2"
                 style="
                    position: absolute;
                    width: {{ $logo2SizePct }}%;
                    height: auto;
                    max-height: 90%;
                    object-fit: contain;
                    top: {{ $school->qr_logo2_y ?? 50 }}%;
                    left: {{ $school->qr_logo2_x ?? 75 }}%;
                    transform: translate(-50%, -50%);
                    cursor: default;
                 "
                 crossorigin="anonymous" draggable="false">
         @endif
    </div>

    {{-- School Name + Major --}}
    <div style="
        position: absolute;
        top: 76px;
        left: 10px; right: 10px;
        text-align: center;
        z-index: 5;
        padding: 0 16px;
    ">
        <p style="
            color: #ffffff;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.03em;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        ">{{ $school->name }}</p>

        @if($showMajor && $major)
        <p style="
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin: 0;
        ">{{ $major }}</p>
        @endif
    </div>

    {{-- QR Code Box (Center overlap) --}}
    @php
        $qrData = '1234567890';
        if ($student) {
            $model = $student->student ?? null;
            if ($model) {
                $qrData = !empty($model->nisn) ? $model->nisn : (!empty($model->nis) ? $model->nis : '1234567890');
            }
        }
        $qrBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(140)->margin(0)->generate($qrData));
    @endphp

    <div style="
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #ffffff;
        padding: 10px;
        border-radius: 14px;
        z-index: 10;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 160px;
        height: 160px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    ">
        <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Code" width="140" height="140" crossorigin="anonymous">
    </div>

    {{-- Student Name & Info (Bottom Area) --}}
    <div style="
        position: absolute;
        bottom: 24px;
        left: 0; right: 0;
        text-align: center;
        z-index: 5;
        padding: 0 20px;
    ">
        <p style="
            color: #1e3a8a;
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 16px 0;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        ">{{ $student ? $student->name : 'NAMA SISWA' }}</p>

        <div style="
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 24px;
        ">
            <div style="text-align: left; color: #1e3a8a; font-size: 12px; font-weight: 500; line-height: 1.8;">
                <div>NIS</div>
                <div>NISN</div>
            </div>
            <div style="text-align: right; color: #1e3a8a; font-size: 12px; font-weight: 700; line-height: 1.8;">
                <div>{{ $student && $student->student ? ($student->student->nis ?? '-') : '232410057' }}</div>
                <div>{{ $student && $student->student ? ($student->student->nisn ?? '-') : '0061947259' }}</div>
            </div>
        </div>
    </div>
</div>