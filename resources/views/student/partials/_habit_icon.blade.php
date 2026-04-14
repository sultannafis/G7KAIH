{{--
    Partial: resources/views/student/partials/_habit_icon.blade.php

    Usage:
        @include('student.partials._habit_icon', ['label' => $label, 'habitName' => $habit->name])
        @include('student.partials._habit_icon', ['label' => $label, 'habitName' => $habit->name, 'class' => 'w-8 h-8'])

    Variables:
        $label     — nama item atau nama habit (string)
        $habitName — nama habit induk (string)
        $class     — ukuran icon, default 'w-6 h-6'
--}}
@php
    $lower      = strtolower($label ?? '');
    $lowerHabit = strtolower($habitName ?? '');
    $iconClass  = $class ?? 'w-6 h-6';
@endphp

{{-- ── Waktu Sholat ─────────────────────────────────────────────── --}}
@if(str_contains($lower, 'subuh') || str_contains($lower, 'fajr'))
    {{-- Sunrise --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1M4.22 4.22l.707.707M18.364 18.364l.707.707M1 12h1m20 0h1M4.22 19.78l.707-.707M18.364 5.636l.707-.707"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8a4 4 0 100 8 4 4 0 000-8z" opacity=".4"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17h18M8 20h8"/>
    </svg>

@elseif(str_contains($lower, 'dzuhur') || str_contains($lower, 'zuhur') || str_contains($lower, 'dhuhr'))
    {{-- Sun at noon --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="4"/>
        <path stroke-linecap="round" d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
    </svg>

@elseif(str_contains($lower, 'ashar') || str_contains($lower, 'asr'))
    {{-- Sun afternoon --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="4"/>
        <path stroke-linecap="round" d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        <path stroke-linecap="round" d="M3 17h18" opacity=".5"/>
    </svg>

@elseif(str_contains($lower, 'maghrib'))
    {{-- Sunset --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a5 5 0 110 10A5 5 0 0112 7z" opacity=".4"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2M4.93 4.93l1.41 1.41M2 12h2M4.93 19.07l1.41-1.41M19.07 4.93l-1.41 1.41M22 12h-2M19.07 19.07l-1.41-1.41"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 18h18M7 21h10"/>
    </svg>

@elseif(str_contains($lower, 'isya') || str_contains($lower, 'isha'))
    {{-- Moon --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
    </svg>

@elseif(str_contains($lower, 'berjamaah') || str_contains($lower, 'jamaah'))
    {{-- People praying together --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>

@elseif(str_contains($lowerHabit, 'sholat') || str_contains($lowerHabit, 'ibadah') || str_contains($lowerHabit, 'prayer'))
    {{-- General prayer --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C9 6 7 9 7 12c0 2.5 1 4.5 2.5 6M12 2c3 4 5 7 5 10 0 2.5-1 4.5-2.5 6M9.5 18h5"/>
        <circle cx="12" cy="4" r="1" fill="currentColor"/>
    </svg>

{{-- ── Makanan & Minuman ────────────────────────────────────────── --}}
@elseif(str_contains($lower, 'sarapan') || str_contains($lower, 'breakfast'))
    {{-- Coffee cup --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>
        <line x1="6" y1="1" x2="6" y2="4" stroke-linecap="round"/>
        <line x1="10" y1="1" x2="10" y2="4" stroke-linecap="round"/>
        <line x1="14" y1="1" x2="14" y2="4" stroke-linecap="round"/>
    </svg>

@elseif(str_contains($lower, 'berbagi makanan') || str_contains($lower, 'berbagi'))
    {{-- Gift / sharing --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 013 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
    </svg>

@elseif(str_contains($lower, 'makan siang') || str_contains($lower, 'lunch'))
    {{-- Fork & knife --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 002-2V2M7 2v20M21 15V2a5 5 0 00-5 5v6h3v7"/>
    </svg>

@elseif(str_contains($lower, 'makan malam') || str_contains($lower, 'dinner') || str_contains($lower, 'makan'))
    {{-- Fork & knife --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 002-2V2M7 2v20M21 15V2a5 5 0 00-5 5v6h3v7"/>
    </svg>

{{-- ── Tidur & Bangun ───────────────────────────────────────────── --}}
@elseif(str_contains($lower, 'bangun') || str_contains($lower, 'wake'))
    {{-- Alarm clock --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="13" r="8"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4l2 2"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3L2 6M22 6l-3-3"/>
    </svg>

@elseif(str_contains($lower, 'tidur') || str_contains($lower, 'sleep') || str_contains($lower, 'istirahat'))
    {{-- Bed --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2 20v-8a2 2 0 012-2h16a2 2 0 012 2v8M2 20h20M2 12V8a2 2 0 012-2h6v6"/>
    </svg>

{{-- ── Olahraga & Kesehatan ─────────────────────────────────────── --}}
@elseif(str_contains($lower, 'olahraga') || str_contains($lower, 'sport') || str_contains($lower, 'gym') || str_contains($lower, 'lari') || str_contains($lower, 'senam'))
    {{-- Running figure --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <circle cx="13" cy="4" r="1.5"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 19l2-5 3 2 2-4M5 9l3-2 2 3 4-2 3 1"/>
    </svg>

{{-- ── Belajar ──────────────────────────────────────────────────── --}}
@elseif(str_contains($lower, 'belajar') || str_contains($lower, 'study') || str_contains($lower, 'membaca') || str_contains($lower, 'baca'))
    {{-- Open book --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>

{{-- ── Kegiatan Bermasyarakat ───────────────────────────────────── --}}
@elseif(str_contains($lower, 'menolong') || str_contains($lower, 'tolong'))
    {{-- Helping hand --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 013 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
    </svg>

@elseif(str_contains($lower, 'karang taruna') || str_contains($lower, 'pengajian') || str_contains($lower, 'masjid'))
    {{-- Religious / community --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L2 19h20L12 2z"/>
        <line x1="12" y1="9" x2="12" y2="13" stroke-linecap="round"/>
        <circle cx="12" cy="15.5" r=".5" fill="currentColor"/>
    </svg>

@elseif(str_contains($lower, 'ta\'ziyah') || str_contains($lower, 'taziyah') || str_contains($lower, 'melayat'))
    {{-- Heart --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    </svg>

@elseif(str_contains($lower, 'sedekah'))
    {{-- Gift --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12v10H4V12M2 7h20v5H2zM12 22V7M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/>
    </svg>

@elseif(str_contains($lowerHabit, 'bermasyarakat') || str_contains($lowerHabit, 'sosial') || str_contains($lowerHabit, 'komunitas'))
    {{-- Community / users --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>

{{-- ── Kegiatan Sekolah ─────────────────────────────────────────── --}}
@elseif(str_contains($lower, 'piket') || str_contains($lower, 'kebersihan') || str_contains($lower, 'kerja bakti'))
    {{-- Broom --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M8 8L3 21l9-3M8 8l8-8 6 6-3.5 3.5"/>
    </svg>

@elseif(str_contains($lower, 'osis') || str_contains($lower, 'rapat') || str_contains($lower, 'organisasi'))
    {{-- Briefcase --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
    </svg>

@elseif(str_contains($lower, 'minat') || str_contains($lower, 'bakat') || str_contains($lower, 'pengembangan') || str_contains($lower, 'ekskul'))
    {{-- Star --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
    </svg>

{{-- ── Default ───────────────────────────────────────────────────── --}}
@else
    {{-- Verified badge / checklist --}}
    <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
    </svg>
@endif