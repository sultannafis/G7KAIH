<div class="space-y-8">
    <!-- Profile Info Section -->
    <div id="info" class="scroll-mt-10">
        @include('profile._partials.info-form', [
            'user' => $user,
            'action' => route('profile.masteradmin.update')
        ])
    </div>

    <!-- Security Section -->
    <div id="security" class="scroll-mt-10">
        @include('profile._partials.password-form', [
            'action' => route('profile.masteradmin.password')
        ])
    </div>
</div>
