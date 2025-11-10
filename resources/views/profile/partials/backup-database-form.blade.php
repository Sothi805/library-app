<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Backup Database') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Download a complete backup of the database as an SQL file. This includes all books, members, borrows, and system data.') }}
        </p>
    </header>

    <div x-data="{ showBackupModal: false }">
        <x-primary-button @click="showBackupModal = true">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-base">download</span>
                {{ __('Download Database Backup') }}
            </span>
        </x-primary-button>

        @if (session('backup-error'))
            <p class="text-sm text-red-600 mt-2">
                {{ session('backup-error') }}
            </p>
        @endif

        {{-- Modal --}}
        <template x-teleport="body">
            <div
                x-show="showBackupModal"
                x-transition.opacity
                x-cloak
                class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm"
                @keydown.escape.window="showBackupModal = false"
                @click="showBackupModal = false"
            >
                <!-- Modal panel -->
                <div
                    @click.stop
                    x-transition.scale
                    class="bg-white/30 backdrop-blur-sm rounded-xl shadow w-full max-w-md p-6"
                    role="dialog"
                    aria-modal="true"
                >
                    <h2 class="text-lg font-semibold text-gray-900">Confirm Database Backup</h2>

                    <p class="mt-3 text-sm text-gray-700">
                        Please enter your password to confirm you want to download the database backup.
                    </p>

                    <div class="mt-4" x-data="{ password: '' }">
                        <div>
                            <x-input-label for="backup_password" value="Enter your password" />
                            <x-text-input
                                id="backup_password"
                                x-model="password"
                                type="password"
                                placeholder="Password"
                                class="mt-1 block w-full"
                            />
                        </div>

                        @if($errors->databaseBackup->has('password'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->databaseBackup->first('password') }}</p>
                        @endif

                        <div class="mt-6 flex justify-end gap-3">
                            <x-secondary-button @click="showBackupModal = false">
                                Cancel
                            </x-secondary-button>

                            <form method="POST" action="{{ route('profile.backup') }}">
                                @csrf

                                <input type="hidden" name="password" x-bind:value="password" />

                                <x-danger-button
                                    type="submit"
                                    :disabled="true"
                                    x-bind:disabled="password === ''"
                                    x-bind:class="{
                                        'opacity-50 cursor-not-allowed': password === ''
                                    }"
                                >
                                    Download Backup
                                </x-danger-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="text-xs text-gray-500">
        <p><strong>Note:</strong> The backup file will contain sensitive information. Store it securely.</p>
        <p class="mt-1">Backup includes: Users, Books, Members, Categories, Borrows, and all related data.</p>
    </div>
</section>
