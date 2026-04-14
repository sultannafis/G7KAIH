<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('schools', function (Blueprint $table) {
    if (!Schema::hasColumn('schools', 'qr_logo1_size')) {
        $table->decimal('qr_logo1_size', 5, 2)->nullable()->default(15);
        $table->decimal('qr_logo2_size', 5, 2)->nullable()->default(15);
    }
});
echo "Done";
