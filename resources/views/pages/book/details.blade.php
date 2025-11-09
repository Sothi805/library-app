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
            <div class="space-x-4">
                <a href="{{ route('books.edit', $book) }}">
                    <x-primary-button>
                        Edit Book
                    </x-primary-button>
                </a>
                <a href="{{ route('books.index') }}">
                    <x-secondary-button>
                        Go Back
                    </x-secondary-button>
                </a>
            </div>
        </div>

        {{-- Welcome End --}}

        {{-- Book Details Start --}}
        <div class="flex gap-4">
            <div class="w-1/4">
                @if ($book->cover_path)
                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Book Cover"
                        class="bg-white/10 backdrop-blur-sm shadow rounded-md object-contain"
                        style="aspect-ratio: 1/1.414; width: 100%;">
                @else
                    <div
                        class="w-full aspect-[1/1.414] bg-gray-200 flex items-center justify-center text-gray-500 bg-white/10 backdrop-blur-sm shadow rounded-md">
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
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->created_at->diffForHumans() }}
                            </h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Added by</h3>
                            <h2 class="text-base text-gray-600 font-semibold">
                                {{ trim(($book->user?->first_name ?? '') . ' ' . ($book->user?->middle_name ?? '') . ' ' . ($book->user?->last_name ?? '')) }}
                            </h2>
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Source</h3>
                            <h2 class="text-base text-gray-600 font-semibold">{{ $book->source }}</h2>
                        </div>
                    </div>
                </div>
                {{-- Book Details End --}}

                {{-- Book Description Start --}}

                <div class="bg-white/10 backdrop-blur-sm p-4 space-y-2 shadow rounded-md">
                    <h2 class="text-lg font-bold">Book Description</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $book->description ?? 'No description available.' }}
                    </p>
                </div>

                {{-- Book Description End --}}

                {{-- Current Borrowers Start --}}
                <div class="bg-white/10 backdrop-blur-sm p-4 space-y-2 shadow rounded-md">
                    <h2 class="text-lg font-bold">Current Borrowers</h2>
                    <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                        <table class="w-full table-auto text-sm text-left text-text-light-primary">
                            <thead class="bg-primary text-white uppercase text-xs">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Member ID</th>
                                    <th scope="col" class="px-4 py-2 text-center">Book Condition</th>
                                    <th scope="col" class="px-4 py-2 text-center">Borrowed</th>
                                    <th scope="col" class="px-4 py-2 text-center">Borrows For</th>
                                    <th scope="col" class="px-4 py-2 text-center">Borrow From</th>
                                    <th scope="col" class="px-4 py-2 rounded-tr-lg text-center">Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-border-dark/10">

                                <tr class="hover:bg-tertiary/10 transition-colors">
                                    <td class="px-4 py-2 text-center">M00001</td>

                                    <td class="px-4 py-2 text-center">
                                        <span
                                            class="text-xs py-1 px-2 rounded-full font-bold
                                                    {{ $condition === 'As New'
                                                        ? 'bg-purple-600 text-purple-100'
                                                        : ($condition === 'Fine'
                                                            ? 'bg-blue-600 text-blue-100'
                                                            : ($condition === 'Very Good'
                                                                ? 'bg-green-600 text-green-100'
                                                                : ($condition === 'Good'
                                                                    ? 'bg-orange-600 text-orange-100'
                                                                    : ($condition === 'Fair'
                                                                        ? 'bg-yellow-600 text-yellow-100'
                                                                        : 'bg-red-600 text-red-100')))) }}">
                                            {{ $condition }}
                                        </span>

                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        {{ $book->created_at->diffForHumans() }}</td>
                                    <td class="px-4 py-2 text-center">14 days</td>
                                    <td class="px-4 py-2 text-center">
                                        {{ trim(($book->user?->first_name ?? '') . ' ' . ($book->user?->middle_name ?? '') . ' ' . ($book->user?->last_name ?? '')) }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a class="hover:underline"
                                            href="{{ route('books.show', $book->id) }}">details</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Current Borrowers End --}}

                {{-- Borrow History Start --}}
                <div class="bg-white/10 backdrop-blur-sm p-4 space-y-2 shadow rounded-md">
                    <h2 class="text-lg font-bold">Borrow History</h2>
                    <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                        <table class="w-full table-auto text-sm text-left text-text-light-primary">
                            <thead class="bg-primary text-white uppercase text-xs">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Member ID</th>
                                    <th scope="col" class="px-4 py-2 text-center">Borrowed Condition</th>
                                    <th scope="col" class="px-4 py-2 text-center">Returned Condition</th>
                                    <th scope="col" class="px-4 py-2 text-center">Set to Return Date</th>
                                    <th scope="col" class="px-4 py-2 text-center">Actual Return Date</th>
                                    <th scope="col" class="px-4 py-2 text-center">Status</th>
                                    <th scope="col" class="px-4 py-2 text-center">Received By</th>
                                    <th scope="col" class="px-4 py-2 rounded-tr-lg text-center">Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-border-dark/10">

                                <tr class="hover:bg-tertiary/10 transition-colors">
                                    <td class="px-4 py-2 text-center">M00002</td>

                                    <td class="px-4 py-2 text-center">
                                        <span
                                            class="text-xs py-1 px-2 rounded-full font-bold
                                                    {{ $condition === 'As New'
                                                        ? 'bg-purple-600 text-purple-100'
                                                        : ($condition === 'Fine'
                                                            ? 'bg-blue-600 text-blue-100'
                                                            : ($condition === 'Very Good'
                                                                ? 'bg-green-600 text-green-100'
                                                                : ($condition === 'Good'
                                                                    ? 'bg-orange-600 text-orange-100'
                                                                    : ($condition === 'Fair'
                                                                        ? 'bg-yellow-600 text-yellow-100'
                                                                        : 'bg-red-600 text-red-100')))) }}">
                                            {{ $condition }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <span
                                            class="text-xs py-1 px-2 rounded-full font-bold
                                                    {{ $condition === 'As New'
                                                        ? 'bg-purple-600 text-purple-100'
                                                        : ($condition === 'Fine'
                                                            ? 'bg-blue-600 text-blue-100'
                                                            : ($condition === 'Very Good'
                                                                ? 'bg-green-600 text-green-100'
                                                                : ($condition === 'Good'
                                                                    ? 'bg-orange-600 text-orange-100'
                                                                    : ($condition === 'Fair'
                                                                        ? 'bg-yellow-600 text-yellow-100'
                                                                        : 'bg-red-600 text-red-100')))) }}">
                                            {{ $condition }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        10/09/25
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        13/09/25
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                            Late 3 days
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        {{ trim(($book->user?->first_name ?? '') . ' ' . ($book->user?->middle_name ?? '') . ' ' . ($book->user?->last_name ?? '')) }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a class="hover:underline"
                                            href="{{ route('books.show', $book->id) }}">details</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Borrow History End --}}


            </div>
        </div>

</x-app-layout>
