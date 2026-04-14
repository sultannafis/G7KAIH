<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('religion');
            $table->string('signature_url')->nullable()->after('avatar_url');
            $table->unsignedBigInteger('signature_media_id')->nullable()->after('signature_url');

            $table->foreign('signature_media_id')
                  ->references('id')
                  ->on('media_files')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['signature_media_id']);
            $table->dropColumn([
                'avatar_url',
                'signature_url',
                'signature_media_id'
            ]);
        });
    }
};