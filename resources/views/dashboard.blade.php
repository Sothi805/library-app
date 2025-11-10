<x-app-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Welcome Start --}}
        <div class="mb-4">
            <h1 class="text-3xl font-bold">Welcome, <span class="text-primary">{{ Auth::user()->first_name }}
                    {{ Auth::user()->last_name }}</span>!</h1>
            <p class="text-base text-gray-600">Here's an overview of your library's activities.</p>
        </div>
        {{-- Welcome End --}}

        {{-- Book Statistics --}}
        <div class="space-y-2 mt-4">
            <h1 class="font-bold text-base">Books by Category</h1>
            <div class="cards grid grid-cols-5 gap-4 mb-4">
                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-primary font-semibold">Total Books</h4>
                    <h1 class="text-xl font-bold">{{ number_format($totalBooks) }}</h1>
                    <p class="text-xs text-gray-600">{{ number_format($totalCopies) }} total copies</p>
                </div>

                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-secondary font-semibold">Currently Borrowed</h4>
                    <h1 class="text-xl font-bold">{{ number_format($activeBorrows) }}</h1>
                    <p class="text-xs text-blue-600 font-semibold">{{ number_format($borrowedCopies) }} copies out</p>
                </div>

                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-green-600 font-semibold">Total Returned</h4>
                    <h1 class="text-xl font-bold">{{ number_format($returnedBooks) }}</h1>
                    <p class="text-xs text-gray-600">All time returns</p>
                </div>

                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs {{ $overdueBorrows > 0 ? 'text-red-600' : 'text-green-600' }} font-semibold">
                        Overdue Books</h4>
                    <h1 class="text-xl font-bold">{{ number_format($overdueBorrows) }}</h1>
                    <p class="text-xs {{ $overdueBorrows > 0 ? 'text-red-600' : 'text-green-600' }} font-semibold">
                        {{ $overdueBorrows > 0 ? 'Action Required' : 'All clear' }}
                    </p>
                </div>

                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-gray-600 font-semibold">Lost</h4>
                    <h1 class="text-xl font-bold">{{ number_format($lostBooks) }}</h1>
                    <p class="text-xs text-gray-600">Books marked as lost</p>
                </div>
            </div>
        </div>

        {{-- Books by Category --}}
        @if ($booksByCategory->isNotEmpty())
            <div class="space-y-2 mt-4">
                <h1 class="font-bold text-base">Books by Category</h1>
                <div class="overflow-x-auto p-0.5">
                    <div class="grid grid-cols-5 gap-4 min-w-max">
                        @foreach ($booksByCategory as $category)
                            <div class="p-4 sm:rounded-lg bg-white/30 backdrop-blur-xs shadow min-w-[180px]">
                                <h3 class="font-semibold text-sm">{{ $category->name ?? 'Uncategorized' }}</h3>
                                <p class="text-2xl font-bold text-primary">{{ $category->count }}</p>
                                <p class="text-xs text-gray-600">{{ round(($category->count / $totalBooks) * 100, 1) }}% of
                                    total</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Member Statistics --}}
        <div class="space-y-2 mt-4">
            <h1 class="font-bold text-base">Member Statistics</h1>
            <div class="cards grid grid-cols-4 gap-4">
                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-primary font-semibold">Total Members</h4>
                    <h1 class="text-xl font-bold">{{ number_format($totalMembers) }}</h1>
                    <p class="text-xs text-gray-600">Registered members</p>
                </div>

                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-green-600 font-semibold">Active Members</h4>
                    <h1 class="text-xl font-bold">{{ number_format($activeMembers) }}</h1>
                    <p class="text-xs text-green-600 font-semibold">
                        {{ $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0 }}% of total</p>
                </div>

                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-accent font-semibold">Inactive Members</h4>
                    <h1 class="text-xl font-bold">{{ number_format($inactiveMembers) }}</h1>
                    <p class="text-xs text-gray-600">No recent activity</p>
                </div>

                <div class="card space-y-2 p-4 bg-white/30 backdrop-blur-xs shadow sm:rounded-lg">
                    <h4 class="text-xs text-blue-600 font-semibold">Available Copies</h4>
                    <h1 class="text-xl font-bold">{{ number_format($availableCopies) }}</h1>
                    <p class="text-xs text-gray-600">Ready to borrow</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-8">
            {{-- Most Popular Book Start --}}
            <div class="col-span-2 space-y-2 p-4 bg-white/30 backdrop-blur-xs overflow-hidden shadow sm:rounded-lg">
                <h1 class="font-bold text-base">Top 10 Most Borrowed Books</h1>
                <div
                    class="overflow-x-auto rounded-lg border border-border-dark/20 bg-background-light/30 backdrop-blur-xs shadow">
                    <table class="w-full table-fixed text-sm text-left text-text-light-primary">
                        <thead class="bg-primary backdrop-blur-xs shadow text-white uppercase text-xs">
                            <tr>
                                <th scope="col" class="px-6 py-2 rounded-tl-lg w-[7.5%]">Rank</th>
                                <th scope="col" class="px-6 py-2 w-[15%]">Book ID</th>
                                <th scope="col" class="px-6 py-2 w-[37.5%]">Book Title</th>
                                <th scope="col" class="px-6 py-2 w-[25%]">Borrowed (Times)</th>
                                <th scope="col" class="px-6 py-2 rounded-tr-lg text-center w-[15%]">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-border-dark/10">
                            @forelse($topBooks as $index => $book)
                                <tr class="hover:bg-tertiary/10 backdrop-blur-xs transition-colors">
                                    <td class="px-6 py-2">{{ $index + 1 }}</td>
                                    <th class="px-6 py-2">{{ $book->book_id }}</th>
                                    <td class="px-6 py-2">
                                        <div
                                            class="font-semibold text-sm line-clamp-1 w-full overflow-hidden text-ellipsis whitespace-nowrap">
                                            {{ $book->title }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 text-center">{{ $book->borrow_count }}</td>
                                    <td class="px-6 py-2 text-center">
                                        <a class="hover:underline" href="{{ route('books.show', $book) }}">details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-2 text-center text-gray-500 italic">
                                        No borrow data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Most Popular Book End --}}

            {{-- Most Recent Borrowed Book Start --}}
            <div class="p-4 bg-white/30 backdrop-blur-xs space-y-2 overflow-hidden shadow sm:rounded-lg">
                <h1 class="font-bold text-base">Recent Borrowed Books</h1>
                @forelse($recentBorrows as $borrow)
                    <div
                        class="border rounded px-2 py-1 border-border-dark/20 bg-background-light/30 backdrop-blur-xs shadow">
                        <h2 class="font-semibold text-sm line-clamp-1 text-primary">{{ $borrow->book->title }}</h2>
                        <h3 class="text-xs text-gray-600">{{ $borrow->borrowed_date->format('M d, Y') }}</h3>
                    </div>
                @empty
                    <p class="text-gray-500 italic text-sm">No recent borrows</p>
                @endforelse
            </div>
            {{-- Most Recent Borrowed Book End --}}
        </div>

    </div>

</x-app-layout>
