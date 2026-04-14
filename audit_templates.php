<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->handle(Illuminate\Http\Request::capture());

echo "AUDIT TEMPLATES FOR AccountCreated\n";
echo "===================================\n";

$templates = App\Models\NotificationTemplate::where('event', 'AccountCreated')->get();

foreach ($templates as $t) {
    echo "ID: {$t->id}\n";
    echo "School ID: " . ($t->school_id ?? 'NULL') . "\n";
    echo "Channel: {$t->channel}\n";
    echo "Target Role: " . json_encode($t->target_role) . "\n";
    echo "Active: " . ($t->is_active ? 'Yes' : 'No') . "\n";
    echo "-----------------------------------\n";
}
