{{--
    Partial: resources/views/student/partials/_habit_card.blade.php
    Variables: $card, $idx

    LOGIKA TOMBOL:
    - Habit TIME-BASED  → tombol "Sudah Melakukan" = form POST langsung (tanpa foto/deskripsi).
                          Sistem baca waktu → tentukan rule → buat submission otomatis.
    - Habit ACTION/MANUAL → redirect ke halaman create (butuh deskripsi + foto).
    - Multi-select         → redirect ke halaman create (butuh pilih kegiatan).
--}}
@php
    $habit         = $card['habit'];
    $item          = $card['habit_item'];
    $submitted     = $card['submitted'];
    $submission    = $card['submission'];
    $isTime        = $card['type'] === 'time';
    $activeRule    = $card['current_rule'] ?? null;
    $isMultiSelect = $card['is_multi_select'] ?? false;
    $canSubmit     = $card['can_submit'] ?? true;
    $opensAt       = $card['opens_at'] ?? null;

    $label     = $item?->name ?? $habit->name;
    $habitName = $habit->name;

    $statusMap = [
        'pending_parent'   => ['label' => 'Menunggu Ortu',   'bg' => 'rgba(255,251,235,.8)', 'color' => '#92400e', 'border' => 'rgba(251,191,36,.4)'],
        'parent_rejected'  => ['label' => 'Ditolak Ortu',    'bg' => 'rgba(255,241,242,.8)', 'color' => '#be123c', 'border' => 'rgba(253,164,175,.4)'],
        'pending_teacher'  => ['label' => 'Menunggu Guru',   'bg' => 'rgba(224,242,254,.8)', 'color' => '#0c4a6e', 'border' => 'rgba(186,230,253,.4)'],
        'teacher_valid'    => ['label' => 'Disetujui Guru',  'bg' => 'rgba(236,253,245,.8)', 'color' => '#065f46', 'border' => 'rgba(167,243,208,.5)'],
        'teacher_rejected' => ['label' => 'Ditolak Guru',    'bg' => 'rgba(255,241,242,.8)', 'color' => '#be123c', 'border' => 'rgba(253,164,175,.4)'],
    ];
    $st = $submission
        ? ($statusMap[$submission->status] ?? ['label' => $submission->status, 'bg' => 'rgba(240,249,255,.6)', 'color' => '#0369a1', 'border' => 'rgba(186,230,253,.5)'])
        : null;

    $createUrl = route('student.habits.submit.create', ['habit' => $habit->id, 'from' => 'today']);
    if (!$isMultiSelect && $item) $createUrl .= '&habit_item_id=' . $item->id;

    $quickSubmitUrl = route('student.habits.quick-submit', $habit);
@endphp

<div class="habit-card rounded-3xl overflow-hidden flex flex-col"
     style="
         animation: fadeSlideUp 0.35s ease both;
         animation-delay: {{ $idx * 55 }}ms;
         background: {{ $submitted ? 'rgba(236,253,245,.78)' : 'rgba(255,255,255,.72)' }};
         backdrop-filter: blur(20px);
         -webkit-backdrop-filter: blur(20px);
         border: 1.5px solid {{ $submitted ? 'rgba(167,243,208,.6)' : 'rgba(255,255,255,.85)' }};
         box-shadow: 0 4px 24px {{ $submitted ? 'rgba(16,185,129,.08)' : 'rgba(14,165,233,.06)' }}, 0 1px 4px rgba(0,0,0,.04);
         transition: transform .2s ease, box-shadow .2s ease;
         {{ !$canSubmit && !$submitted ? 'opacity:.75;' : '' }}
     "
     onmouseover="if(!this.dataset.nohover){this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 36px rgba(14,165,233,.12),0 2px 8px rgba(0,0,0,.06)'}"
     onmouseout="this.style.transform='';this.style.boxShadow=''">

    {{-- Card Body --}}
    <div style="padding:20px 20px 16px;flex:1">

        {{-- Header --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px">
            <div style="display:flex;align-items:center;gap:12px;min-width:0">
                <div style="
                    width:48px;height:48px;border-radius:14px;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;
                    {{ $submitted
                        ? 'background:rgba(209,250,229,.8);color:#059669;'
                        : (!$canSubmit
                            ? 'background:rgba(240,249,255,.6);color:#7dd3fc;'
                            : 'background:rgba(224,242,254,.7);color:#0369a1;') }}
                ">
                    @include('student.partials._habit_icon', ['label' => $label, 'habitName' => $habitName, 'class' => 'w-6 h-6'])
                </div>
                <div style="min-width:0">
                    <h3 style="font-size:.9rem;font-weight:700;color:#0c4a6e;line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $label }}</h3>
                    @if($item || $isMultiSelect)
                    <p style="font-size:.72rem;font-weight:500;color:#7dd3fc;margin-top:2px">{{ $habitName }}</p>
                    @endif
                </div>
            </div>
            @if($submitted)
            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 4px 12px rgba(16,185,129,.35);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            @endif
        </div>

        {{-- Time-based rules --}}
        @if($isTime && $card['rules']->isNotEmpty())
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:12px">
            @foreach($card['rules'] as $rule)
            @php
                $isActiveRule = $activeRule && $activeRule->id === $rule->id;
                $ruleBg = match($rule->priority) {
                    1 => $isActiveRule ? 'rgba(209,250,229,.85)' : 'rgba(240,253,244,.6)',
                    2 => $isActiveRule ? 'rgba(254,243,199,.85)' : 'rgba(254,252,232,.6)',
                    default => $isActiveRule ? 'rgba(255,228,230,.85)' : 'rgba(255,241,242,.6)',
                };
                $ruleBorder = match($rule->priority) {
                    1 => $isActiveRule ? 'rgba(52,211,153,.6)' : 'rgba(167,243,208,.4)',
                    2 => $isActiveRule ? 'rgba(251,191,36,.5)' : 'rgba(253,230,138,.4)',
                    default => $isActiveRule ? 'rgba(248,113,113,.5)' : 'rgba(253,164,175,.4)',
                };
                $ruleColor = match($rule->priority) { 1 => '#065f46', 2 => '#92400e', default => '#9f1239' };
            @endphp
            <div style="display:flex;align-items:center;justify-content:space-between;padding:7px 12px;border-radius:10px;font-size:.75rem;background:{{ $ruleBg }};border:1.5px solid {{ $ruleBorder }};color:{{ $ruleColor }};{{ $isActiveRule ? 'font-weight:700;' : 'font-weight:500;' }}">
                <span style="display:flex;align-items:center;gap:6px">
                    @if($isActiveRule)
                    <span style="width:6px;height:6px;border-radius:50%;background:currentColor;animation:pulse-ring-sm 1.4s ease infinite;flex-shrink:0"></span>
                    @endif
                    <span>{{ $rule->name }}</span>
                    @if($rule->start_time)
                    <span style="opacity:.7">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $rule->start_time)->format('H:i') }}
                        @if($rule->end_time) – {{ \Carbon\Carbon::createFromFormat('H:i:s', $rule->end_time)->format('H:i') }}@endif
                    </span>
                    @endif
                </span>
                <span style="font-weight:800">{{ $rule->point }} poin</span>
            </div>
            @endforeach
        </div>

        {{-- Time status indicator --}}
        @if(!$submitted)
            @if($activeRule)
            <div style="display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:10px;font-size:.75rem;font-weight:600;color:#065f46;background:rgba(209,250,229,.6);border:1.5px solid rgba(52,211,153,.4);margin-bottom:6px">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                Berlaku sekarang: <strong>{{ $activeRule->name }}</strong> (+{{ $activeRule->point }})
            </div>
            @elseif(!$canSubmit && $opensAt)
            <div style="display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:10px;font-size:.75rem;font-weight:600;color:#0369a1;background:rgba(224,242,254,.6);border:1.5px solid rgba(147,197,253,.4);margin-bottom:6px">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                Buka pukul <strong>{{ $opensAt }}</strong>
            </div>
            @else
            <div style="display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:10px;font-size:.75rem;font-weight:600;color:#9f1239;background:rgba(255,241,242,.6);border:1.5px solid rgba(253,164,175,.4);margin-bottom:6px">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Di luar rentang waktu yang tersedia
            </div>
            @endif
        @endif

        @elseif(!$isMultiSelect)
        {{-- Action-based indicator --}}
        <div style="display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:10px;font-size:.75rem;font-weight:600;color:#5b21b6;background:rgba(245,243,255,.6);border:1.5px solid rgba(196,181,253,.4);margin-bottom:6px">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Wajib isi deskripsi aktivitas
        </div>
        @endif

        {{-- Submission status --}}
        @if($submitted && $submission && $st)
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:10px">
            <span style="display:inline-flex;align-items:center;padding:4px 12px;border-radius:20px;font-size:.72rem;font-weight:700;background:{{ $st['bg'] }};color:{{ $st['color'] }};border:1px solid {{ $st['border'] }}">{{ $st['label'] }}</span>
            @if($submission->point > 0)
            <span style="font-size:.875rem;font-weight:800;color:#059669">+{{ $submission->point }} poin</span>
            @endif
        </div>
        @endif
    </div>

    {{-- Card Footer --}}
    <div style="padding:12px 20px;background:rgba(240,249,255,.4);border-top:1.5px solid rgba(186,230,253,.3)">

        @if($submitted && $submission)
        {{-- Sudah submit → lihat detail --}}
        <a href="{{ route('student.habits.submission.show', $submission) }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:9px 16px;border-radius:14px;font-size:.8rem;font-weight:700;color:#059669;text-decoration:none;background:rgba(209,250,229,.6);border:1.5px solid rgba(52,211,153,.4);transition:all .18s ease"
           onmouseover="this.style.background='rgba(209,250,229,.9)'"
           onmouseout="this.style.background='rgba(209,250,229,.6)'">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Lihat Detail Submission
        </a>

        @elseif(!$canSubmit)
        {{-- Belum waktunya --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:9px 16px;border-radius:14px;font-size:.8rem;font-weight:600;color:#94a3b8;background:rgba(241,245,249,.6);border:1.5px solid rgba(203,213,225,.5);cursor:not-allowed;user-select:none">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
            {{ $opensAt ? 'Buka pukul ' . $opensAt : 'Belum tersedia' }}
        </div>

        @elseif($isTime)
        {{-- ✅ TIME-BASED: form POST langsung, tidak redirect, tidak butuh foto/deskripsi --}}
        <form action="{{ $quickSubmitUrl }}" method="POST">
            @csrf
            <input type="hidden" name="habit_item_id" value="{{ $item?->id }}">
            <button type="submit"
                    style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:9px 16px;border-radius:14px;font-size:.8rem;font-weight:700;color:white;background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 18px rgba(16,185,129,.3);border:none;cursor:pointer;transition:all .2s ease"
                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(16,185,129,.4)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 6px 18px rgba(16,185,129,.3)'"
                    onclick="this.disabled=true;this.style.opacity='.7';this.innerHTML='<svg width=\'14\' height=\'14\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'white\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 4v1m0 14v1M4 12H3m18 0h-1M6.34 6.34l-.71-.71M18.36 18.36l-.71-.71M6.34 17.66l-.71.71M18.36 5.64l-.71.71\'></path></svg> Menyimpan…';this.closest('form').submit()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Sudah Melakukan
            </button>
        </form>

        @elseif($isMultiSelect)
        {{-- Multi-select → ke create untuk pilih kegiatan --}}
        <a href="{{ $createUrl }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:9px 16px;border-radius:14px;font-size:.8rem;font-weight:700;color:white;text-decoration:none;background:linear-gradient(135deg,#60a5fa,#3b82f6);box-shadow:0 6px 18px rgba(59,130,246,.3);transition:all .2s ease"
           onmouseover="this.style.transform='translateY(-1px)'"
           onmouseout="this.style.transform=''">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Pilih Kegiatan
        </a>

        @else
        {{-- ACTION-BASED: ke halaman create, butuh deskripsi + foto --}}
        <a href="{{ $createUrl }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:9px 16px;border-radius:14px;font-size:.8rem;font-weight:700;color:white;text-decoration:none;background:linear-gradient(135deg,#34d399,#10b981);box-shadow:0 6px 18px rgba(16,185,129,.3);transition:all .2s ease"
           onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(16,185,129,.4)'"
           onmouseout="this.style.transform='';this.style.boxShadow='0 6px 18px rgba(16,185,129,.3)'">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Sudah Melakukan
        </a>
        @endif
    </div>
</div>

@once
@push('styles')
<style>
@keyframes pulse-ring-sm {
    0%   { transform: scale(1);   opacity: .8; }
    100% { transform: scale(2.5); opacity: 0;  }
}
</style>
@endpush
@endonce