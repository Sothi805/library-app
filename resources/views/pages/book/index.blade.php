<x-app-layout>
    <x-slot name="title">
        Book Inventory
    </x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold">Inventory Management Table</h1>
        </div>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Welcome Start --}}

        {{-- Welcome End --}}

        {{-- Book Inventory Table Start --}}
        <div class="p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex justify-between items-center mb-2">
                <h1 class="font-bold text-base">Book Inventory Table</h1>
                <a href="{{ route('books.create') }}">
                    <x-primary-button>
                        Add Book
                    </x-primary-button>
                </a>
            </div>
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
                                    {{ $book->user?->first_name . ' ' . ($book->user->middle_name ? $book->user->middle_name : ' ') . ' ' . $book->user?->last_name }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a class="hover:underline" href="{{ route('books.show', $book->id) }}">details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
