{{-- Gunakan shared/submission_show agar tampilan detail submission siswa identik dengan parent/guru --}}
@include('shared.submission_show', [
    'submission'   => $submission,
    'backRoute'    => route('student.habits.history'),
    'canValidate'  => false,
    'roleLabel'    => 'Siswa',
    // Tambahkan variabel lain jika dibutuhkan oleh shared/submission_show
])