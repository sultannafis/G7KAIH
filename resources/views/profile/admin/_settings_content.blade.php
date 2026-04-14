<div class="space-y-8">
    <!-- Profile Info Section -->
    <div id="info" class="scroll-mt-10">
        @include('profile._partials.info-form', [
            'user' => $user,
            'action' => route('profile.admin.update'),
            'extraFields' => $user->school ? view('profile.admin._extra_fields', ['user' => $user]) : null
        ])
    </div>
    
    <!-- School Settings Section -->
    @include('profile.admin._school_settings', ['school' => $school, 'provinces' => $provinces])

    <!-- Security Section -->
    <div id="security" class="scroll-mt-10">
        @include('profile._partials.password-form', [
            'action' => route('profile.admin.password')
        ])
    </div>
</div>
