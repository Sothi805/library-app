<x-app-layout>
    <x-slot name="title">
        Book Details
    </x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold">Book Details</h1>
        </div>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Welcome Start --}}
        <div class="mb-4 flex justify-between items-center">
            <div></div>
            <a href="{{ route('books.index') }}">
                <x-secondary-button>
                    Go Back
                </x-secondary-button>
            </a>
        </div>

        <div class="flex gap-4">
            <div class="w-1/4 bg-white/10 backdrop-blur-sm shadow rounded-md">
                @if ($book->cover_path)
                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Book Cover"
                        class="rounded-md shadow-md object-contain" style="aspect-ratio: 1/1.414; width: 100%;">
                @else
                    <div
                        class="w-full aspect-[1/1.414] bg-gray-200 flex items-center justify-center text-gray-500 rounded-md">
                        No Cover
                    </div>
                @endif
            </div>
            <div class="w-3/4 space-y-4">
                <div class="bg-white/10 backdrop-blur-sm p-4 space-y-4 shadow rounded-md">
                    <h1 class="text-2xl font-bold">{{ $book->title }}</h1>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <h3 class="text-gray-600 text-sm">Book Code</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->book_id }}</h2>
                        </div>

                        <div>
                            <h3 class="text-gray-600 text-sm">Total Copies</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->total_copies }}</h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Available Copies</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->available_copies }}</h2>
                        </div>
                         <div>
                            <h3 class="text-gray-600 text-sm">Author</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->author }}</h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Category</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->category->name }}</h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Status</h3>
                            <h2 class="text-base text-gray-600 font-semibold">
                                    <span
                                        class="text-xs py-1 px-2 rounded-full font-bold
                                        {{ $book->available_copies > 0 ? 'bg-green-600 text-green-100' : 'bg-red-600 text-red-100' }}">
                                        {{ $book->available_copies > 0 ? 'Available' : 'Out of Stock' }}
                                    </span>
                            </h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Published Year</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->published_year }}</h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Added</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ \Carbon\Carbon::parse($book->added_date)->diffForHumans() }}</h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Added by</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->user?->first_name . ' ' . ($book->user->middle_name ? $book->user->middle_name : ' ') . ' ' . $book->user?->last_name }}</h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Source</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->source }}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 space-y-2 shadow rounded-md">

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
