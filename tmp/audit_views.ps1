
$views = Get-ChildItem -Path C:\laragon\www\g7kaih\resources\views -Filter *.blade.php -Recurse
$missing = @()

foreach ($view in $views) {
    $content = Get-Content $view.FullName -Raw
    if ($content -notmatch "x-app-layout|x-guest-layout|layouts\.app|layouts\.guest|toast-notification") {
        $missing += $view.FullName
    }
}

$missing | Out-File $env:TEMP\missing_notifications.txt
    
