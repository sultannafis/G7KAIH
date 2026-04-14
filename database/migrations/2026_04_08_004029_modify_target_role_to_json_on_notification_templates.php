<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->json('target_roles')->nullable()->after('target_role');
        });

        // Migrate data and group by duplicates
        $templates = \Illuminate\Support\Facades\DB::table('notification_templates')->get();
        // Group by school_id, event, channel, message_template
        $grouped = $templates->groupBy(function ($item) {
            return ($item->school_id ?? 'global') . '_' . $item->event . '_' . $item->channel . '_' . md5($item->message_template);
        });

        foreach ($grouped as $group) {
            $first = $group->first();
            $roles = $group->pluck('target_role')->unique()->values()->toArray();
            
            \Illuminate\Support\Facades\DB::table('notification_templates')
                ->where('id', $first->id)
                ->update(['target_roles' => json_encode($roles)]);

            // Delete duplicates
            $duplicateIds = $group->pluck('id')->filter(fn($id) => $id !== $first->id)->toArray();
            if (count($duplicateIds) > 0) {
                \Illuminate\Support\Facades\DB::table('notification_templates')
                    ->whereIn('id', $duplicateIds)
                    ->delete();
            }
        }

        Schema::table('notification_templates', function (Blueprint $table) {
            $table->dropColumn('target_role');
        });

        Schema::table('notification_templates', function (Blueprint $table) {
            $table->renameColumn('target_roles', 'target_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->string('target_role_old')->nullable()->after('target_role');
        });

        // Demigrate data back to string
        $templates = \Illuminate\Support\Facades\DB::table('notification_templates')->get();
        foreach ($templates as $template) {
            $roles = json_decode($template->target_role, true);
            $primaryRole = is_array($roles) && count($roles) > 0 ? $roles[0] : 'siswa';
            
            \Illuminate\Support\Facades\DB::table('notification_templates')
                ->where('id', $template->id)
                ->update(['target_role_old' => $primaryRole]);
        }

        Schema::table('notification_templates', function (Blueprint $table) {
            $table->dropColumn('target_role');
        });

        Schema::table('notification_templates', function (Blueprint $table) {
            $table->renameColumn('target_role_old', 'target_role');
        });
    }
};
