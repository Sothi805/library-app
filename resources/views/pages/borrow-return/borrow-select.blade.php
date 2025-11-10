<x-app-layout>
    <x-slot name="title">
        Select Book to Borrow
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h1 class="text-xl font-semibold">Select Book to Borrow</h1>
        </div>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Back Button --}}
        <div class="flex justify-between items-center">
            <div></div>
            <a href="{{ url()->previous() }}">
                <x-secondary-button>
                    Go Back
                </x-secondary-button>
            </a>
        </div>

        {{-- Search and Controls --}}
        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-md shadow space-y-4">
            <form method="GET" action="{{ route('borrow-return.borrow-select') }}" class="space-y-4">
                {{-- Search Filters --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="search" value="Search Books" />
                        <x-text-input id="search" name="search" type="text" class="mt-1 block w-full text-sm"
                            placeholder="Search by ID, title, or author" value="{{ $search ?? '' }}" />
                    </div>

                    <div>
                        <x-input-label for="category" value="Category" />
                        <select id="category" name="category"
                            class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <x-primary-button type="submit">
                            Search
                        </x-primary-button>

                        @if($search || $category)
                            <a href="{{ route('borrow-return.borrow-select') }}">
                                <x-secondary-button type="button">
                                    Clear
                                </x-secondary-button>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Sort and Display Controls --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="sort_by" value="Sort By" />
                        <select id="sort_by" name="sort_by"
                            class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                            onchange="this.form.submit()">
                            <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Date Added</option>
                            <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Title</option>
                            <option value="author" {{ $sortBy === 'author' ? 'selected' : '' }}>Author</option>
                            <option value="book_id" {{ $sortBy === 'book_id' ? 'selected' : '' }}>Book ID</option>
                            <option value="available_copies" {{ $sortBy === 'available_copies' ? 'selected' : '' }}>Available Copies</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="sort_order" value="Order" />
                        <select id="sort_order" name="sort_order"
                            class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                            onchange="this.form.submit()">
                            <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Ascending (A-Z, 0-9)</option>
                            <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Descending (Z-A, 9-0)</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="per_page" value="Show" />
                        <select id="per_page" name="per_page"
                            class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                            onchange="this.form.submit()">
                            <option value="12" {{ $perPage == 12 ? 'selected' : '' }}>12 per page</option>
                            <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>24 per page</option>
                            <option value="48" {{ $perPage == 48 ? 'selected' : '' }}>48 per page</option>
                            <option value="96" {{ $perPage == 96 ? 'selected' : '' }}>96 per page</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- Books Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($books as $book)
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-md shadow flex gap-4">
                    @if ($book->cover_path)
                        <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Book Cover"
                            class="w-24 h-32 object-cover rounded">
                    @else
                        <div class="w-24 h-32 bg-gray-200 flex items-center justify-center rounded">
                            No Cover
                        </div>
                    @endif
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold">{{ $book->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $book->author }}</p>
                            <p class="text-sm font-mono text-gray-600">{{ $book->book_id }}</p>
                            <p class="text-sm text-gray-600">Available: {{ $book->available_copies }}</p>
                        </div>
                        @if($book->available_copies > 0)
                            <a href="{{ route('borrow-return.borrow.form', $book) }}"
                                class="inline-flex justify-center">
                                <x-primary-button class="w-full">
                                    Borrow
                                </x-primary-button>
                            </a>
                        @else
                            <x-secondary-button class="w-full opacity-50 cursor-not-allowed" disabled>
                                Out of Stock
                            </x-secondary-button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 italic py-8">
                    No books found
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>
</x-app-layout>
