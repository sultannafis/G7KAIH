<?php
// Replace route names in shared views
$dir = __DIR__ . '/../resources/views/shared/user-management';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $newContent = str_replace('school-admin.user-management.', 'user-management.', $content);
        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done!\n";
