<x-app-layout>
    <x-slot name="title">
        Add Member
    </x-slot>

    <x-slot name="header">
        <h1 class="text-xl font-bold">Add a New Member</h1>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Member Form --}}
        <form method="POST" action="{{ route('members.store') }}" class="p-6 bg-white shadow-sm sm:rounded-lg space-y-6"
            autocomplete="off">
            @csrf

            {{-- Section Header --}}
            <div class="flex justify-between items-center mb-2">
                <h1 class="font-bold text-base">Member Information</h1>
                <a href="{{ url()->previous() }}">
                    <x-secondary-button>
                        Go Back
                    </x-secondary-button>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-6">
                {{-- First Name --}}
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                        :value="old('first_name')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>

                {{-- Middle Name --}}
                <div>
                    <x-input-label for="middle_name" :value="__('Middle Name')" />
                    <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full"
                        :value="old('middle_name')" />
                    <div class="mt-1 text-sm text-gray-500">Optional</div>
                    <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
                </div>

                {{-- Last Name --}}
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                        :value="old('last_name')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>

                {{-- Gender --}}
                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select id="gender" name="gender"
                        class="mt-1 block w-full px-2 py-2 bg-background-light border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 rounded-md"
                        required>
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                </div>

                {{-- Email --}}
                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email')" />
                    <div class="mt-1 text-sm text-gray-500">Optional</div>
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                {{-- Phone --}}
                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                        :value="old('phone')" />
                    <div class="mt-1 text-sm text-gray-500">Optional</div>
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-4">
                <x-primary-button>
                    Save Member
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
