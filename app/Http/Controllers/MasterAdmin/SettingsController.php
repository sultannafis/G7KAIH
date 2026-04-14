<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /** GET /masteradmin/settings */
    public function index()
    {
        $setting          = AiSetting::instance();
        $activeSchools    = School::where('status', 'active')->orderBy('name')->get();
        $schools          = School::orderBy('name')->get();
        $selectedSchoolIds = DB::table('ai_validation_schools')->pluck('school_id')->toArray();

        // Redirect user accessing specific settings paths back to single-page index with anchor hash
        if (request()->path() !== 'masteradmin/settings' && request()->routeIs('masteradmin.settings.*')) {
            if (request()->routeIs('masteradmin.settings.ai')) return redirect()->route('masteradmin.settings.index')->withFragment('section-ai');
            if (request()->routeIs('masteradmin.settings.notifications')) return redirect()->route('masteradmin.settings.index')->withFragment('section-notifications');
        }

        return view('masteradmin.settings.index', compact('setting', 'activeSchools', 'schools', 'selectedSchoolIds'));
    }

    /** GET /masteradmin/settings/ai */
    public function aiPage()
    {
        return redirect()->route('masteradmin.settings.index')->withFragment('section-ai');
    }

    /** POST /masteradmin/settings/ai */
    public function aiSave(Request $request)
    {
        $request->validate([
            'app_name'               => 'nullable|string|max:100',
            'app_context'            => 'nullable|string|max:3000',
            'ai_chat_system_prompt'  => 'nullable|string|max:5000',
            'ai_validation_prompt'   => 'nullable|string|max:5000',
            'ai_validation_scope'    => 'nullable|in:all,selected',
            'selected_schools'       => 'nullable|array',
            'selected_schools.*'     => 'integer|exists:schools,id',
        ]);

        $setting = AiSetting::instance();
        $setting->update([
            'is_enabled'             => $request->boolean('is_enabled'),
            'app_name'               => $request->input('app_name', $setting->app_name),
            'app_context'            => $request->input('app_context', $setting->app_context),
            'ai_chat_system_prompt'  => $request->input('ai_chat_system_prompt', $setting->ai_chat_system_prompt),
            'ai_validation_enabled'  => $request->boolean('ai_validation_enabled'),
            'ai_validation_prompt'   => $request->input('ai_validation_prompt', $setting->ai_validation_prompt),
            'ai_validation_scope'    => $request->input('ai_validation_scope', 'all'),
        ]);

        // Update daftar sekolah yang diizinkan
        DB::table('ai_validation_schools')->truncate();
        if ($request->boolean('ai_validation_enabled') && $request->input('ai_validation_scope') === 'selected') {
            $schoolIds = $request->input('selected_schools', []);
            foreach ($schoolIds as $schoolId) {
                DB::table('ai_validation_schools')->insertOrIgnore([
                    'school_id'  => $schoolId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Pengaturan AI berhasil disimpan!');
    }

    /** GET /masteradmin/settings/notifications */
    public function notificationPage()
    {
        return redirect()->route('masteradmin.settings.index')->withFragment('section-notifications');
    }

    /** POST /masteradmin/settings/notifications */
    public function notificationSave(Request $request)
    {
        $request->validate([
            'wa_schools' => 'nullable|array',
            'wa_schools.*' => 'integer|exists:schools,id',
            'email_schools' => 'nullable|array',
            'email_schools.*' => 'integer|exists:schools,id',
        ]);

        $waSchools = $request->input('wa_schools', []);
        $emailSchools = $request->input('email_schools', []);

        DB::beginTransaction();
        try {
            // Update WA settings
            School::whereIn('id', $waSchools)->update(['is_wa_enabled' => true]);
            School::whereNotIn('id', $waSchools)->update(['is_wa_enabled' => false]);

            // Update Email settings
            School::whereIn('id', $emailSchools)->update(['is_email_enabled' => true]);
            School::whereNotIn('id', $emailSchools)->update(['is_email_enabled' => false]);

            DB::commit();
            return back()->with('success', 'Pengaturan Notifikasi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pengaturan notifikasi.');
        }
    }
}
