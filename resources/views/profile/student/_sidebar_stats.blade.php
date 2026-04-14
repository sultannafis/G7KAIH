<div class="text-center">
    <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest leading-none">NISN</p>
    <p class="text-xs font-bold text-slate-700 mt-1">{{ $student->nisn ?? '-' }}</p>
</div>
<div class="text-center border-l border-slate-50">
    <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest leading-none">Kelas</p>
    <p class="text-xs font-bold text-slate-700 mt-1">{{ $student->class_name ?? '-' }}</p>
</div>
