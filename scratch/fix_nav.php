<?php
$file = __DIR__ . '/../resources/views/layouts/navigation.blade.php';
$c = file_get_contents($file);

// 1. Replace masteradmin nav links to use unified routes
$c = str_replace("route('masteradmin.user-management.teachers.index')", "route('user-management.teachers.index')", $c);
$c = str_replace("route('masteradmin.user-management.students.index')", "route('user-management.students.index')", $c);
$c = str_replace("route('masteradmin.user-management.parents.index')", "route('user-management.parents.index')", $c);

// 2. Replace school-admin nav links to use unified routes
$c = str_replace("route('school-admin.user-management.teachers.index')", "route('user-management.teachers.index')", $c);
$c = str_replace("route('school-admin.user-management.students.index')", "route('user-management.students.index')", $c);
$c = str_replace("route('school-admin.user-management.parents.index')", "route('user-management.parents.index')", $c);

// 3. Update routeIs checks for active states
$c = str_replace("request()->routeIs('masteradmin.user-management.*')", "request()->routeIs('user-management.*')", $c);
$c = str_replace("request()->routeIs('school-admin.user-management.*')", "request()->routeIs('user-management.*')", $c);

file_put_contents($file, $c);
echo "Navigation updated!\n";
