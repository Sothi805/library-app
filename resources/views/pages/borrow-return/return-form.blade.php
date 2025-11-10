<x-app-layout>
    <x-slot name="title">
        Return Book
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h1 class="text-xl font-semibold">Return Book</h1>
        </div>
    </x-slot>

    <div class="p-8">
        <div class="mx-auto">
            {{-- Book Info Card --}}
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-md shadow mb-4">
                <div class="flex gap-4">
                    @if ($book->cover_path)
                        <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Book Cover"
                            class="w-24 h-32 object-cover rounded">
                    @else
                        <div class="w-24 h-32 bg-gray-200 flex items-center justify-center rounded">
                            No Cover
                        </div>
                    @endif
                    <div class="space-y-2">
                        <div>
                            <h2 class="text-lg font-bold">{{ $book->title }}</h2>
                            <p class="text-sm text-gray-600">Book ID: {{ $book->book_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Member ID: {{ $borrow->member->member_code }}</p>
                            <p class="text-sm text-gray-600">Borrowed: {{ $borrow->borrowed_date->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-600">Due: {{ $borrow->due_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Return Form --}}
            <form action="{{ route('borrow-return.return', $book) }}" method="POST"
                class="bg-white/10 backdrop-blur-sm p-6 rounded-md shadow">
                @csrf
                <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="return_status" value="Return Status" />
                        <select id="return_status" name="return_status"
                            class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                            required>
                            <option value="returned" {{ $borrow->status === 'returned' ? 'selected' : '' }}>Mark as Returned</option>
                            <option value="borrowed" {{ $borrow->status === 'borrowed' ? 'selected' : '' }}>Mark as Not Returned (Revert)</option>
                        </select>
                        <x-input-error :messages="$errors->get('return_status')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Use "Not Returned" to undo a mistaken return</p>
                    </div>

                    <div>
                        <x-input-label for="condition_after" value="Current Book Condition" />
                        <select id="condition_after" name="condition_after"
                            class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                            required>
                            <option value="">Select condition</option>
                            <option value="As new" {{ $borrow->condition_after === 'As new' ? 'selected' : '' }}>As new</option>
                            <option value="Good" {{ $borrow->condition_after === 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ $borrow->condition_after === 'Fair' ? 'selected' : '' }}>Fair</option>
                            <option value="Poor" {{ $borrow->condition_after === 'Poor' ? 'selected' : '' }}>Poor</option>
                            <option value="Damaged" {{ $borrow->condition_after === 'Damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="Lost" {{ $borrow->condition_after === 'Lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                        <x-input-error :messages="$errors->get('condition_after')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Original condition: {{ $borrow->condition_before }}</p>
                    </div>

                    <div>
                        <x-input-label for="returned_date" value="Returned Date" />
                        <input type="date" id="returned_date" name="returned_date"
                            value="{{ $borrow->returned_date ? $borrow->returned_date->format('Y-m-d') : now()->format('Y-m-d') }}"
                            class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                            required>
                        <x-input-error :messages="$errors->get('returned_date')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-4">
                    <a href="{{ url()->previous() }}">
                        <x-secondary-button type="button">
                            Cancel
                        </x-secondary-button>
                    </a>

                    <x-primary-button>
                        Update Return
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
