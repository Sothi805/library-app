<x-app-layout>
    <x-slot name="title">Return Book</x-slot>

    <x-slot name="header">
        <h1 class="text-xl font-bold">Return Book</h1>
    </x-slot>

    <div class="p-8 space-y-4">

        <form method="POST" action="{{ route('books.return', $book) }}"
              class="p-6 bg-white shadow-sm sm:rounded-lg space-y-6">
            @csrf

            <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">

            <div class="flex justify-between items-center mb-2">
                <h1 class="font-bold text-base">Return Information</h1>

                <a href="{{ route('books.show', $book) }}">
                    <x-secondary-button>Go Back</x-secondary-button>
                </a>
            </div>

            <div class="space-y-4">

                {{-- CURRENT CONDITION BEFORE --}}
                <div>
                    <x-input-label for="condition_before" value="Condition When Borrowed" />

                    <input type="text" value="{{ $borrow->condition_before }}"
                        class="w-full border-gray-300 rounded-md bg-gray-100"
                        disabled>
                </div>

                {{-- CONDITION AFTER RETURN --}}
                <div>
                    <x-input-label for="condition_after" value="Book Condition (After Return)" />

                    <select id="condition_after" name="condition_after"
                        class="w-full border-gray-300 rounded-md" required>
                        <option value="">-- Select Condition --</option>
                        <option value="As New">As New</option>
                        <option value="Good">Good</option>
                        <option value="Worn">Worn</option>
                        <option value="Damaged">Damaged</option>
                    </select>
                </div>

            </div>

            <div class="flex justify-end">
                <x-primary-button>Confirm Return</x-primary-button>
            </div>

        </form>

    </div>
</x-app-layout>
