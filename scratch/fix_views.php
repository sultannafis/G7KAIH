<?php
// Fix students index view
$file = __DIR__ . '/../resources/views/shared/user-management/students/index.blade.php';
$c = file_get_contents($file);

// 1. Header subtitle - replace Administrasi with role-conditional
$c = str_replace(
    "<p class=\"text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1\">Administrasi</p>",
    "<p class=\"text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1\">{{ Auth::user()->role === 'masteradmin' ? 'Master Admin · User Management' : 'Administrasi' }}</p>",
    $c
);
$c = str_replace(
    '<h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">Manajemen Siswa</h1>',
    '<h1 class="text-2xl sm:text-3xl font-bold text-sky-800" style="letter-spacing:-.02em">{{ Auth::user()->role === \'masteradmin\' ? \'Semua Data Siswa\' : \'Manajemen Siswa\' }}</h1>',
    $c
);
$c = str_replace(
    '<p class="text-sky-500 font-medium mt-1 text-sm">Kelola data siswa di sekolah Anda</p>',
    '<p class="text-sky-500 font-medium mt-1 text-sm">{{ Auth::user()->role === \'masteradmin\' ? \'Data siswa dari seluruh sekolah\' : \'Kelola data siswa di sekolah Anda\' }}</p>',
    $c
);

// 2. Wrap action buttons in admin-only check - find the div with action buttons
// Add @if before first action button div
$c = preg_replace(
    '/(\s*)<div class="flex flex-wrap gap-2">\s*\n\s*<a href="\{\{ route\(\'user-management\.students\.create\'\)/',
    '$1@if(Auth::user()->role === \'admin\')' . "\n" . '$1<div class="flex flex-wrap gap-2">' . "\n" . '$1    <a href="{{ route(\'user-management.students.create\')',
    $c,
    1
);
// Close the @if after the div closes (before </div> of header)
$c = preg_replace(
    '/(Template\s*<\/a>\s*\n\s*<\/div>)\s*\n(\s*<\/div>\s*\n\s*<\/x-slot>)/',
    '$1' . "\n" . '            @endif' . "\n" . '$2',
    $c,
    1
);

// 3. Fix stat cards for masteradmin
$c = str_replace(
    '$school = Auth::user()->school;' . "\n" . '                $totalStudents',
    'if(Auth::user()->role === \'masteradmin\') {' . "\n" .
    '                    $totalStudents   = \App\Models\User::where(\'role\',\'siswa\')->count();' . "\n" .
    '                    $activeStudents  = \App\Models\User::where(\'role\',\'siswa\')->where(\'is_active\',true)->count();' . "\n" .
    '                    $inactiveStudents= \App\Models\User::where(\'role\',\'siswa\')->where(\'is_active\',false)->count();' . "\n" .
    '                } else {' . "\n" .
    '                $school = Auth::user()->school;' . "\n" . '                $totalStudents',
    $c
);
// Close the else block after inactiveStudents
$c = preg_replace(
    '/(\$inactiveStudents\s*=\s*\\\\App\\\\Models\\\\User::where\(\'school_id\',\$school->id\)->where\(\'role\',\'siswa\'\)->where\(\'is_active\',false\)->count\(\);\s*\n)(\s*@endphp)/',
    '$1                }' . "\n" . '$2',
    $c
);

// 4. Add school filter for masteradmin in filter section - before status filter
$schoolFilter = '                    @if(Auth::user()->role === \'masteradmin\' && isset($schools) && $schools->count())
                    <div class="w-full sm:w-52">
                        <label class="sky-label">Sekolah</label>
                        <select name="school_id" class="sky-select">
                            <option value="">Semua Sekolah</option>
                            @foreach($schools as $sch)
                                <option value="{{ $sch->id }}" {{ request(\'school_id\') == $sch->id ? \'selected\' : \'\' }}>{{ $sch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
';
$c = str_replace(
    '                    <div class="w-full sm:w-40">' . "\n" . '                        <label class="sky-label">Status</label>',
    $schoolFilter . '                    <div class="w-full sm:w-40">' . "\n" . '                        <label class="sky-label">Status</label>',
    $c,
);

// 5. Add school column in table header for masteradmin
$c = str_replace(
    '<th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider w-10">No</th>' . "\n" .
    '                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Nama Siswa</th>',
    '<th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider w-10">No</th>' . "\n" .
    '                                    @if(Auth::user()->role === \'masteradmin\')' . "\n" .
    '                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden sm:table-cell">Sekolah</th>' . "\n" .
    '                                    @endif' . "\n" .
    '                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Nama Siswa</th>',
    $c
);

// 6. Wrap edit/delete/toggle action buttons in admin-only check
// Find the edit link and wrap from there to end of delete form
$c = str_replace(
    '<a href="{{ route(\'user-management.students.edit',
    '@if(Auth::user()->role === \'admin\')' . "\n" . '                                            <a href="{{ route(\'user-management.students.edit',
    $c
);
// Close @endif after the delete form
$c = str_replace(
    "                                            </form>\n                                        </div>\n                                    </td>",
    "                                            </form>\n                                            @endif\n                                        </div>\n                                    </td>",
    $c,
    $count
);

// 7. Fix empty state
$c = str_replace(
    "@if(!request()->hasAny(['search','status']))\n                            <a href=\"{{ route('user-management.students.create') }}\"",
    "@if(Auth::user()->role === 'admin' && !request()->hasAny(['search','status']))\n                            <a href=\"{{ route('user-management.students.create') }}\"",
    $c
);

// Fix reset filter check
$c = str_replace("hasAny(['search','status'])", "hasAny(['search','status','school_id','grade_level'])", $c);

file_put_contents($file, $c);
echo "Updated students index\n";

// ─── Fix students show ───
$file = __DIR__ . '/../resources/views/shared/user-management/students/show.blade.php';
$c = file_get_contents($file);
$c = str_replace(
    '<a href="{{ route(\'user-management.students.edit',
    '@if(Auth::user()->role === \'admin\')' . "\n" . '                <a href="{{ route(\'user-management.students.edit',
    $c,
    $cnt
);
if ($cnt > 0) {
    $c = preg_replace(
        '/(Edit\s*<\/a>)\s*\n(\s*<a href="\{\{ route\(\'user-management\.students\.index)/',
        '$1' . "\n" . '                @endif' . "\n" . '$2',
        $c,
        1
    );
}
file_put_contents($file, $c);
echo "Updated students show\n";

// ─── Fix parents index ───
$file = __DIR__ . '/../resources/views/shared/user-management/parents/index.blade.php';
$c = file_get_contents($file);

$c = str_replace(
    "<p class=\"text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1\">Administrasi</p>",
    "<p class=\"text-xs font-bold uppercase tracking-[.15em] text-sky-500 mb-1\">{{ Auth::user()->role === 'masteradmin' ? 'Master Admin · User Management' : 'Administrasi' }}</p>",
    $c
);
$c = str_replace(
    'Manajemen Orang Tua</h1>',
    "{{ Auth::user()->role === 'masteradmin' ? 'Semua Data Orang Tua' : 'Manajemen Orang Tua' }}</h1>",
    $c
);

// Wrap action buttons
$c = preg_replace(
    '/(\s*)<div class="flex flex-wrap gap-2">\s*\n\s*<a href="\{\{ route\(\'user-management\.parents\.create\'\)/',
    '$1@if(Auth::user()->role === \'admin\')' . "\n" . '$1<div class="flex flex-wrap gap-2">' . "\n" . '$1    <a href="{{ route(\'user-management.parents.create\')',
    $c,
    1
);
$c = preg_replace(
    '/(Template\s*<\/a>\s*\n\s*<\/div>)\s*\n(\s*<\/div>\s*\n\s*<\/x-slot>)/',
    '$1' . "\n" . '            @endif' . "\n" . '$2',
    $c,
    1
);

// Fix stat cards
$c = str_replace(
    '$school = Auth::user()->school;' . "\n" . '                $totalParents',
    'if(Auth::user()->role === \'masteradmin\') {' . "\n" .
    '                    $totalParents   = \App\Models\User::where(\'role\',\'orangtua\')->count();' . "\n" .
    '                    $activeParents  = \App\Models\User::where(\'role\',\'orangtua\')->where(\'is_active\',true)->count();' . "\n" .
    '                    $inactiveParents= \App\Models\User::where(\'role\',\'orangtua\')->where(\'is_active\',false)->count();' . "\n" .
    '                } else {' . "\n" .
    '                $school = Auth::user()->school;' . "\n" . '                $totalParents',
    $c
);
$c = preg_replace(
    '/(\$inactiveParents\s*=\s*\\\\App\\\\Models\\\\User::where\(\'school_id\',\$school->id\)->where\(\'role\',\'orangtua\'\)->where\(\'is_active\',false\)->count\(\);\s*\n)(\s*@endphp)/',
    '$1                }' . "\n" . '$2',
    $c
);

// Add school filter
$c = str_replace(
    '                    <div class="w-full sm:w-40">' . "\n" . '                        <label class="sky-label">Status</label>',
    $schoolFilter . '                    <div class="w-full sm:w-40">' . "\n" . '                        <label class="sky-label">Status</label>',
    $c,
);

// Add school column header
$c = str_replace(
    '<th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider w-10">No</th>' . "\n" .
    '                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Nama Orang Tua</th>',
    '<th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider w-10">No</th>' . "\n" .
    '                                    @if(Auth::user()->role === \'masteradmin\')' . "\n" .
    '                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider hidden sm:table-cell">Sekolah</th>' . "\n" .
    '                                    @endif' . "\n" .
    '                                    <th class="px-5 py-4 text-left text-xs font-bold text-sky-500 uppercase tracking-wider">Nama Orang Tua</th>',
    $c
);

// Wrap edit/delete buttons
$c = str_replace(
    '<a href="{{ route(\'user-management.parents.edit',
    '@if(Auth::user()->role === \'admin\')' . "\n" . '                                            <a href="{{ route(\'user-management.parents.edit',
    $c
);
$c = str_replace(
    "                                            </form>\n                                        </div>\n                                    </td>",
    "                                            </form>\n                                            @endif\n                                        </div>\n                                    </td>",
    $c,
    $count
);

// Fix empty state
$c = str_replace(
    "@if(!request()->hasAny(['search','status']))\n                            <a href=\"{{ route('user-management.parents.create') }}\"",
    "@if(Auth::user()->role === 'admin' && !request()->hasAny(['search','status']))\n                            <a href=\"{{ route('user-management.parents.create') }}\"",
    $c
);

$c = str_replace("hasAny(['search','status'])", "hasAny(['search','status','school_id','relationship'])", $c);

file_put_contents($file, $c);
echo "Updated parents index\n";

// ─── Fix parents show ───
$file = __DIR__ . '/../resources/views/shared/user-management/parents/show.blade.php';
$c = file_get_contents($file);
$c = str_replace(
    '<a href="{{ route(\'user-management.parents.edit',
    '@if(Auth::user()->role === \'admin\')' . "\n" . '                <a href="{{ route(\'user-management.parents.edit',
    $c,
    $cnt
);
if ($cnt > 0) {
    $c = preg_replace(
        '/(Edit\s*<\/a>)\s*\n(\s*<a href="\{\{ route\(\'user-management\.parents\.index)/',
        '$1' . "\n" . '                @endif' . "\n" . '$2',
        $c,
        1
    );
}
file_put_contents($file, $c);
echo "Updated parents show\n";

echo "All views updated!\n";
