<x-app-layout>
    <x-slot name="title">
        Edit Member
    </x-slot>

    <x-slot name="header">
        <h1 class="text-xl font-bold">Edit Member: {{ $member->member_code }}</h1>
    </x-slot>

    <div class="p-8 space-y-6">

        {{-- Member Form --}}
        <form method="POST" action="{{ route('members.update', $member) }}"
            class="p-6 bg-white shadow-sm sm:rounded-lg space-y-6" autocomplete="off">

            @csrf
            @method('PUT')

            {{-- Section Header --}}
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="font-bold text-base">Member Information</h1>
                    <p class="text-sm text-gray-500">Member since {{ $member->created_at->format('M d, Y') }}</p>
                </div>

                <a href="{{ url()->previous() }}">
                    <x-secondary-button>Go Back</x-secondary-button>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-6">

                {{-- First Name --}}
                <div>
                    <x-input-label for="first_name" value="First Name" />
                    <x-text-input id="first_name" name="first_name" type="text"
                        value="{{ old('first_name', $member->first_name) }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                {{-- Middle Name --}}
                <div>
                    <x-input-label for="middle_name" value="Middle Name" />
                    <x-text-input id="middle_name" name="middle_name" type="text"
                        value="{{ old('middle_name', $member->middle_name) }}" class="mt-1 block w-full" />
                    <div class="mt-1 text-sm text-gray-500">Optional</div>
                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                </div>

                {{-- Last Name --}}
                <div>
                    <x-input-label for="last_name" value="Last Name" />
                    <x-text-input id="last_name" name="last_name" type="text"
                        value="{{ old('last_name', $member->last_name) }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                {{-- Gender --}}
                <div>
                    <x-input-label for="gender" value="Gender" />
                    <select id="gender" name="gender"
                        class="mt-1 block w-full px-2 py-2 bg-background-light border-gray-300 rounded-md" required>
                        <option value="male" {{ old('gender', $member->gender) === 'male' ? 'selected' : '' }}>Male
                        </option>
                        <option value="female" {{ old('gender', $member->gender) === 'female' ? 'selected' : '' }}>
                            Female</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                {{-- Email --}}
                <div>
                    <x-input-label for="email" value="Email Address" />
                    <x-text-input id="email" name="email" type="email"
                        value="{{ old('email', $member->email) }}" class="mt-1 block w-full" />
                    <div class="mt-1 text-sm text-gray-500">Optional</div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Phone --}}
                <div>
                    <x-input-label for="phone" value="Phone Number" />
                    <x-text-input id="phone" name="phone" type="tel"
                        value="{{ old('phone', $member->phone) }}" class="mt-1 block w-full" />
                    <div class="mt-1 text-sm text-gray-500">Optional</div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                {{-- Status --}}
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status"
                        class="mt-1 block w-full px-2 py-2 bg-background-light border-gray-300 rounded-md" required>
                        <option value="active" {{ old('status', $member->status) === 'active' ? 'selected' : '' }}>
                            Active</option>
                        <option value="inactive" {{ old('status', $member->status) === 'inactive' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>

                    @if ($member->status === 'inactive')
                        <p class="mt-1 text-sm text-red-500">Inactive since
                            {{ $member->inactive_since->format('M d, Y') }}</p>
                    @endif

                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-4">
                <x-primary-button>Update Member</x-primary-button>
            </div>
        </form>

        {{-- Danger Zone Delete Member --}}
        <div class="mt-8">
            <div class="p-6 bg-white/10 backdrop-blur-sm shadow rounded-md">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-red-600">Danger Zone</h2>
                        <p class="text-sm text-gray-500">Once deleted, all member data will be permanently removed.</p>
                    </div>

                    <div class="cursor-pointer">
                        @if ($member->borrows()->where('status', 'borrowed')->exists())
                            <x-danger-button disabled>
                                Cannot Delete (Active Borrows)
                            </x-danger-button>
                        @else
                            <div x-data="{ showDeleteModal: false }">
                                <x-danger-button @click="showDeleteModal = true">
                                    Delete Member
                                </x-danger-button>

                                @include('pages.member.partials.delete-modal', [
                                    'member' => $member,
                                ])
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>






    </div>
</x-app-layout>
