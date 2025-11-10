<template x-teleport="#modal-root">

    <div
        x-show="showDeleteBookModal"
        x-transition.opacity
        x-cloak
        @keydown.escape.window="showDeleteBookModal = false"
        @click="showDeleteBookModal = false"
        class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm"
    >
        <div
            @click.stop
            x-transition.scale
            class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md"
        >
            <h2 class="text-lg font-semibold text-red-600">Delete Book</h2>

            <p class="mt-3 text-sm text-gray-700">
                This action cannot be undone. This will permanently delete this book and all related borrow records.
            </p>

            <div class="mt-4" x-data="{ confirmText: '' }">
                <x-input-label value="Type 'delete book' to confirm" />
                <x-text-input
                    x-model="confirmText"
                    placeholder="delete book"
                    class="mt-1 block w-full"
                />
                <p class="mt-1 text-sm text-gray-500">Case-insensitive</p>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button @click="showDeleteBookModal = false">
                        Cancel
                    </x-secondary-button>

                    <form method="POST" action="{{ route('books.destroy', $book) }}">
                        @csrf
                        @method('DELETE')

                        <x-danger-button
                            type="submit"
                            :disabled="true"
                            x-bind:disabled="confirmText.toLowerCase() !== 'delete book'"
                            x-bind:class="{
                                'opacity-50 cursor-not-allowed': confirmText.toLowerCase() !== 'delete book'
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
