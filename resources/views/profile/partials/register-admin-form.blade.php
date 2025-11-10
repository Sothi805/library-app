<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Register New Admin') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Create a new admin account. Only logged-in administrators can register new users.') }}
        </p>
    </header>

    <form method="post" action="{{ route('admin.register') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
                <x-input-label for="admin_first_name" :value="__('First Name')" />
                <x-text-input id="admin_first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name')" required />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <!-- Middle Name -->
            <div>
                <x-input-label for="admin_middle_name" :value="__('Middle Name')" />
                <x-text-input id="admin_middle_name" name="middle_name" type="text" class="mt-1 block w-full" :value="old('middle_name')" />
                <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Last Name -->
            <div>
                <x-input-label for="admin_last_name" :value="__('Last Name')" />
                <x-text-input id="admin_last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name')" required />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="admin_email" :value="__('Email')" />
                <x-text-input id="admin_email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <x-input-label for="admin_password" :value="__('Password')" />
                <x-text-input id="admin_password" name="password" type="password" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('password')" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="admin_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="admin_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Register Admin') }}</x-primary-button>

            @if (session('status') === 'admin-registered')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >{{ __('Admin registered successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
