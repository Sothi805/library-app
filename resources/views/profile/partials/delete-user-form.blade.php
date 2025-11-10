<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    @if($totalUsers <= 1)
        <x-danger-button disabled class="opacity-50 cursor-not-allowed">
            {{ __('Cannot Delete (Last Admin)') }}
        </x-danger-button>
        <p class="text-sm text-red-600">
            This is the last admin account. At least one admin must remain in the system.
        </p>
    @else
        <div x-data="{ showDeleteAccountModal: false }">
            <x-danger-button @click="showDeleteAccountModal = true">
                {{ __('Delete Account') }}
            </x-danger-button>

            {{-- Modal --}}
            <template x-teleport="body">
                <div
                    x-show="showDeleteAccountModal"
                    x-transition.opacity
                    x-cloak
                    class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm"
                    @keydown.escape.window="showDeleteAccountModal = false"
                    @click="showDeleteAccountModal = false"
                >
                    <!-- Modal panel -->
                    <div
                        @click.stop
                        x-transition.scale
                        class="bg-white/30 backdrop-blur-sm rounded-xl shadow w-full max-w-md p-6"
                        role="dialog"
                        aria-modal="true"
                    >
                        <h2 class="text-lg font-semibold text-red-600">Delete Account</h2>

                        <p class="mt-3 text-sm text-gray-700">
                            This action cannot be undone. This will permanently remove:
                        </p>

                        <ul class="mt-2 text-sm text-gray-700 list-disc list-inside">
                            <li>Your admin account</li>
                            <li>All your activity history</li>
                            <li>All related records</li>
                        </ul>

                        <div class="mt-4" x-data="{ confirmText: '', password: '' }">
                            <div class="space-y-4">
                                <div>
                                    <x-input-label value="Type 'delete account' to confirm" />
                                    <x-text-input
                                        x-model="confirmText"
                                        placeholder="delete account"
                                        class="mt-1 block w-full"
                                    />
                                    <p class="mt-1 text-sm text-gray-500">Case-insensitive</p>
                                </div>

                                <div>
                                    <x-input-label for="delete_password" value="Enter your password" />
                                    <x-text-input
                                        id="delete_password"
                                        x-model="password"
                                        type="password"
                                        placeholder="Password"
                                        class="mt-1 block w-full"
                                    />
                                </div>
                            </div>

                            @if($errors->userDeletion->has('password'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                            @endif

                            @if($errors->has('account_deletion'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('account_deletion') }}</p>
                            @endif

                            <div class="mt-6 flex justify-end gap-3">
                                <x-secondary-button @click="showDeleteAccountModal = false">
                                    Cancel
                                </x-secondary-button>

                                <form method="POST" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" name="password" x-bind:value="password" />

                                    <x-danger-button
                                        type="submit"
                                        :disabled="true"
                                        x-bind:disabled="confirmText.toLowerCase() !== 'delete account' || password === ''"
                                        x-bind:class="{
                                            'opacity-50 cursor-not-allowed': confirmText.toLowerCase() !== 'delete account' || password === ''
                                        }"
                                    >
                                        Delete Permanently
                                    </x-danger-button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif
</section>
