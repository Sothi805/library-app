<x-app-layout>
    <x-slot name="title">
        Book Inventory
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Book Inventory Management
        </h2>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Welcome Start --}}

        {{-- Welcome End --}}

        {{-- Book Inventory Table Start --}}
        <div class="p-4 bg-white/30 backdrop-blur-xs overflow-hidden shadow sm:rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h1 class="font-bold text-base">Book Inventory Table</h1>
                <a href="{{ route('books.create') }}">
                    <x-primary-button>
                        Add Book
                    </x-primary-button>
                </a>
            </div>

            {{-- Search and Controls --}}
            <div class="mb-4 space-y-4">
                <form method="GET" action="{{ route('books.index') }}" class="space-y-4">
                    {{-- Search Filters --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="search" value="Search Books" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full text-base"
                                placeholder="Search by ID, title, or author" value="{{ $search ?? '' }}" />
                        </div>

                        <div>
                            <x-input-label for="category" value="Category" />
                            <select id="category" name="category"
                                class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status"
                                class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">All Status</option>
                                <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Available
                                </option>
                                <option value="out_of_stock" {{ $status === 'out_of_stock' ? 'selected' : '' }}>Out
                                    of Stock</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <x-secondary-button type="submit">
                                Search
                            </x-secondary-button>

                            @if ($search || $category || $status)
                                <a href="{{ route('books.index') }}">
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
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 per page</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per page</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 per page</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per page</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                <table class="w-full table-auto text-sm text-left text-text-light-primary">
                    <thead class="bg-primary text-white uppercase text-xs">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Book ID</th>
                            <th scope="col" class="px-4 py-2">Book Cover</th>
                            <th scope="col" class="px-4 py-2">Book Title</th>
                            <th scope="col" class="px-4 py-2">Author</th>
                            <th scope="col" class="px-4 py-2 text-center">Status</th>
                            <th scope="col" class="px-4 py-2 text-center">Added</th>
                            <th scope="col" class="px-4 py-2 text-center">Added By</th>
                            <th scope="col" class="px-4 py-2 rounded-tr-lg text-center">Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-border-dark/10">
                        @foreach ($books as $book)
                            <tr class="hover:bg-tertiary/10 transition-colors">
                                <td class="px-4 py-2 text-center">{{ $book->book_id }}</td>
                                <th class="px-4 py-2">
                                    @if ($book->cover_path)
                                        <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Book Cover"
                                            class="rounded-md shadow-md object-contain"
                                            style="aspect-ratio: 1/1.414; width: 75px;">
                                    @else
                                        <div
                                            class="w-[75px] aspect-[1/1.414] bg-gray-200 flex items-center justify-center text-gray-500 rounded-md">
                                            No Cover
                                        </div>
                                    @endif
                                </th>
                                <td class="px-4 py-2">
                                    <div
                                        class="font-semibold text-sm line-clamp-1 w-full overflow-hidden text-ellipsis whitespace-nowrap">
                                        {{ $book->title }}
                                    </div>
                                </td>
                                <td class="px-4 py-2">{{ $book->author }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span
                                        class="text-xs py-1 px-2 rounded-full font-bold
                                        {{ $book->available_copies > 0 ? 'bg-green-600 text-green-100' : 'bg-red-600 text-red-100' }}">
                                        {{ $book->available_copies > 0 ? 'Available' : 'Out of Stock' }}
                                    </span>
                                </td>

                                <td class="px-4 py-2 text-center">
                                    {{ $book->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-2 text-center">
                                    {{ trim(($book->user?->first_name ?? '') . ' ' . ($book->user?->middle_name ?? '') . ' ' . ($book->user?->last_name ?? '')) }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a class="hover:underline" href="{{ route('books.show', $book->id) }}">details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
