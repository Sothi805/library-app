{{-- Parent page should define x-data with showDeleteModal somewhere near the trigger --}}
<template x-teleport="#modal-root">
    <div
        x-show="showDeleteModal"
        x-transition.opacity
        x-cloak
        class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm"
        @keydown.escape.window="showDeleteModal = false"
        @click="showDeleteModal = false"
    >
        <!-- Modal panel -->
        <div
            @click.stop
            x-transition.scale
            class="bg-white/30 backdrop-blur-sm rounded-xl shadow w-full max-w-md p-6"
            role="dialog"
            aria-modal="true"
        >
            <h2 class="text-lg font-semibold text-red-600">Delete Member</h2>

            <p class="mt-3 text-sm text-gray-700">
                This action cannot be undone. This will permanently remove:
            </p>

            <ul class="mt-2 text-sm text-gray-700 list-disc list-inside">
                <li>Member profile</li>
                <li>All borrow history</li>
                <li>All related records</li>
            </ul>

            <div class="mt-4" x-data="{ confirmText: '' }">
                <x-input-label value="Type 'delete member' to confirm" />
                <x-text-input
                    x-model="confirmText"
                    placeholder="delete member"
                    class="mt-1 block w-full"
                />
                <p class="mt-1 text-sm text-gray-500">Case-insensitive</p>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button @click="showDeleteModal = false">
                        Cancel
                    </x-secondary-button>

                    <form method="POST" action="{{ route('members.destroy', $member) }}">
                        @csrf
                        @method('DELETE')

                        <x-danger-button
                            type="submit"
                            :disabled="true"
                            x-bind:disabled="confirmText.toLowerCase() !== 'delete member'"
                            x-bind:class="{
                                'opacity-50 cursor-not-allowed': confirmText.toLowerCase() !== 'delete member'
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
