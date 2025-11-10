<x-app-layout>
    <x-slot name="title">
        {{ __("Profile") }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profile
        </h2>
    </x-slot>

    <div class="p-8">
        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white/30 backdrop-blur-sm shadow sm:rounded-lg">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-4 sm:p-8 bg-white/30 backdrop-blur-sm shadow sm:rounded-lg">
                @include('profile.partials.update-password-form')
            </div>

            <div class="p-4 sm:p-8 bg-white/30 backdrop-blur-sm shadow sm:rounded-lg">
                @include('profile.partials.register-admin-form')
            </div>

            <div class="p-4 sm:p-8 bg-white/30 backdrop-blur-sm shadow sm:rounded-lg">
                @include('profile.partials.backup-database-form')
            </div>

            <div class="p-4 sm:p-8 bg-white/30 backdrop-blur-sm shadow sm:rounded-lg">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
