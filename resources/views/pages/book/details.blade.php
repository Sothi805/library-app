<x-app-layout>
    <x-slot name="title">
        Book Details
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h1 class="text-xl font-semibold">Book Details</h1>
        </div>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Welcome Start --}}
        <div class="flex justify-between items-center">
            <div class="space-x-4">
                @if ($book->available_copies > 0)
                    <a href="{{ route('borrow-return.borrow.form', $book) }}">
                        <x-primary-button>Borrow Book</x-primary-button>
                    </a>
                @endif
            </div>

            <div class="space-x-4">
                <a href="{{ route('books.edit', $book) }}">
                    <x-primary-button>
                        Edit Book
                    </x-primary-button>
                </a>
                <a href="{{ route('books.index') }}">
                    <x-secondary-button>
                        Back to Book Inventory
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
                        class="w-full aspect-[1/1.414] flex items-center justify-center text-gray-500 bg-white/10 backdrop-blur-sm shadow rounded-md">
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
                <div class="bg-white/10 backdrop-blur-sm p-4 space-y-4 shadow rounded-md">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold">Current Borrowers</h2>

                        {{-- Per Page Selector --}}
                        @if($currentBorrowers->total() > 0)
                        <form method="GET" action="{{ route('books.show', $book) }}" class="flex items-center space-x-2">
                            <label for="per_page" class="text-sm text-gray-600">Show:</label>
                            <select id="per_page" name="per_page"
                                class="px-2 py-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                                onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>
                        @endif
                    </div>

                    @if ($currentBorrowers->total() > 0)
                        <div
                            class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                            <table class="w-full table-auto text-sm text-left text-text-light-primary">
                                <thead class="bg-primary text-white uppercase text-xs">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Member ID</th>
                                        <th scope="col" class="px-4 py-2 text-center">Borrowed Date</th>
                                        <th scope="col" class="px-4 py-2 text-center">Due Date</th>
                                        <th scope="col" class="px-4 py-2 text-center">Status</th>
                                        <th scope="col" class="px-4 py-2 rounded-tr-lg text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-border-dark/10">
                                    @foreach($currentBorrowers as $borrow)
                                    <tr class="hover:bg-tertiary/10 transition-colors">
                                        <td class="px-4 py-2 text-center">{{ $borrow->member->member_code }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $borrow->borrowed_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $borrow->due_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-center">
                                            @if ($borrow->due_date->isPast())
                                                <span
                                                    class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                                    Overdue ({{ $borrow->due_date->diffForHumans() }})
                                                </span>
                                            @else
                                                <span
                                                    class="text-xs py-1 px-2 rounded-full font-bold bg-green-600 text-green-100">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('borrow-return.return.form', ['book' => $book, 'borrow' => $borrow]) }}" class="text-primary hover:underline">
                                                Return Book
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $currentBorrowers->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 italic">No active borrowers</p>
                    @endif
                </div>
                {{-- Current Borrowers End --}}

                {{-- Borrow History Start --}}
                <div class="bg-white/10 backdrop-blur-sm p-4 space-y-4 shadow rounded-md">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold">Borrow History</h2>

                        {{-- Per Page Selector --}}
                        <form method="GET" action="{{ route('books.show', $book) }}" class="flex items-center space-x-2">
                            <label for="per_page" class="text-sm text-gray-600">Show:</label>
                            <select id="per_page" name="per_page"
                                class="px-2 py-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                                onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                        <table class="w-full table-auto text-sm text-left text-text-light-primary">
                            <thead class="bg-primary text-white uppercase text-xs">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Member ID</th>
                                    <th scope="col" class="px-4 py-2 text-center">Borrowed Date</th>
                                    <th scope="col" class="px-4 py-2 text-center">Due Date</th>
                                    <th scope="col" class="px-4 py-2 text-center">Returned Date</th>
                                    <th scope="col" class="px-4 py-2 text-center">Status</th>
                                    <th scope="col" class="px-4 py-2 rounded-tr-lg text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-border-dark/10">
                                @forelse($borrowHistory as $borrow)
                                    <tr class="hover:bg-tertiary/10 transition-colors">
                                        <td class="px-4 py-2 text-center">{{ $borrow->member->member_code }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $borrow->borrowed_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-center">{{ $borrow->due_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $borrow->returned_date ? $borrow->returned_date->format('M d, Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @if ($borrow->status === 'returned')
                                                @if ($borrow->returned_date->gt($borrow->due_date))
                                                    <span
                                                        class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                                        Returned Late
                                                        ({{ $borrow->returned_date->diffInDays($borrow->due_date) }}
                                                        days)
                                                    </span>
                                                @else
                                                    <span
                                                        class="text-xs py-1 px-2 rounded-full font-bold bg-green-600 text-green-100">
                                                        Returned On Time
                                                    </span>
                                                @endif
                                            @else
                                                @if ($borrow->due_date->isPast())
                                                    <span
                                                        class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                                        Overdue ({{ $borrow->due_date->diffForHumans() }})
                                                    </span>
                                                @else
                                                    <span
                                                        class="text-xs py-1 px-2 rounded-full font-bold bg-yellow-600 text-yellow-100">
                                                        Borrowed
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @if ($borrow->status === 'returned')
                                                <a class="text-primary hover:underline text-sm"
                                                    href="{{ route('borrow-return.return.form', ['book' => $book, 'borrow' => $borrow]) }}">
                                                    Edit Return
                                                </a>
                                            @else
                                                <a class="hover:underline text-sm"
                                                    href="{{ route('members.show', $borrow->member->id) }}">
                                                    View Member
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-center text-gray-500 italic">
                                            No borrow history available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $borrowHistory->links() }}
                    </div>
                </div>
                {{-- Borrow History End --}}

                {{-- Delete Book Section --}}
                <div class="mt-8 border-t pt-8">
                    <div x-data="{ showDeleteBookModal: false }" class="flex items-center justify-between">

                        <div>
                            <h2 class="text-lg font-bold text-red-600">Danger Zone</h2>
                            <p class="text-sm text-gray-500">This action cannot be undone.</p>
                        </div>

                        <x-danger-button @click="showDeleteBookModal = true">
                            Delete Book
                        </x-danger-button>

                        {{-- Modal partial --}}
                        @include('pages.book.partials.delete-modal', ['book' => $book])

                    </div>
                </div>


            </div>
        </div>


    </div>
</x-app-layout>
