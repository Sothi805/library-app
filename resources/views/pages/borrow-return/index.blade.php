<x-app-layout>
    <x-slot name="title">
        Book Borrows & Returns
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h1 class="text-xl font-semibold">Book Borrows & Returns</h1>
        </div>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Tabs for Active Borrows and History --}}
        <div x-data="{ activeTab: 'active' }" class="space-y-4">
            <div class="flex justify-between items-center">
                <div class="flex gap-4 border-b border-border-dark/20">
                    <button @click="activeTab = 'active'"
                        :class="{ 'border-b-2 border-primary text-primary': activeTab === 'active' }"
                        class="px-4 py-2 font-semibold hover:text-primary transition-colors">
                        Active Borrows
                    </button>
                    <button @click="activeTab = 'history'"
                        :class="{ 'border-b-2 border-primary text-primary': activeTab === 'history' }"
                        class="px-4 py-2 font-semibold hover:text-primary transition-colors">
                        Borrow History
                    </button>
                </div>

                <a href="{{ route('borrow-return.borrow-select') }}" class="inline-flex items-center">
                    <x-primary-button>
                        Borrow New Book
                    </x-primary-button>
                </a>

            </div>

            {{-- Active Borrows Table --}}
            <div x-show="activeTab === 'active'" x-cloak>
                <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                    <table class="w-full table-auto text-sm text-left text-text-light-primary">
                        <thead class="bg-primary text-white uppercase text-xs">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Book ID</th>
                                <th scope="col" class="px-4 py-2">Title</th>
                                <th scope="col" class="px-4 py-2 text-center">Member</th>
                                <th scope="col" class="px-4 py-2 text-center">Borrowed Date</th>
                                <th scope="col" class="px-4 py-2 text-center">Due Date</th>
                                <th scope="col" class="px-4 py-2 text-center">Status</th>
                                <th scope="col" class="px-4 py-2 rounded-tr-lg text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-border-dark/10">
                            @forelse($activeBorrows as $borrow)
                                <tr class="hover:bg-tertiary/10 transition-colors">
                                    <td class="px-4 py-2 text-center font-mono">{{ $borrow->book->book_id }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('books.show', $borrow->book) }}"
                                            class="hover:text-primary hover:underline">
                                            {{ $borrow->book->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="{{ route('members.show', $borrow->member) }}"
                                            class="hover:text-primary hover:underline">
                                            {{ $borrow->member->member_code }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->borrowed_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->due_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($borrow->due_date->isPast())
                                            <span class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="text-xs py-1 px-2 rounded-full font-bold bg-yellow-600 text-yellow-100">
                                                Borrowing
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="{{ route('borrow-return.return.form', ['book' => $borrow->book, 'borrow' => $borrow]) }}"
                                            class="text-primary hover:underline">
                                            Return Book
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-2 text-center text-gray-500 italic">
                                        No active borrows
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Borrow History Table --}}
            <div x-show="activeTab === 'history'" x-cloak>
                <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                    <table class="w-full table-auto text-sm text-left text-text-light-primary">
                        <thead class="bg-primary text-white uppercase text-xs">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Book ID</th>
                                <th scope="col" class="px-4 py-2">Title</th>
                                <th scope="col" class="px-4 py-2 text-center">Member</th>
                                <th scope="col" class="px-4 py-2 text-center">Borrowed Date</th>
                                <th scope="col" class="px-4 py-2 text-center">Due Date</th>
                                <th scope="col" class="px-4 py-2 text-center">Returned Date</th>
                                <th scope="col" class="px-4 py-2 text-center">Status</th>
                                <th scope="col" class="px-4 py-2 text-center rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-border-dark/10">
                            @forelse($borrowHistory as $borrow)
                                <tr class="hover:bg-tertiary/10 transition-colors">
                                    <td class="px-4 py-2 text-center font-mono">{{ $borrow->book->book_id }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('books.show', $borrow->book) }}"
                                            class="hover:text-primary hover:underline">
                                            {{ $borrow->book->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="{{ route('members.show', $borrow->member) }}"
                                            class="hover:text-primary hover:underline">
                                            {{ $borrow->member->member_code }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->borrowed_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->due_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        {{ $borrow->returned_date ? $borrow->returned_date->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($borrow->status === 'returned')
                                            @if ($borrow->returned_date->gt($borrow->due_date))
                                                <span class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                                    Returned Late
                                                    ({{ $borrow->returned_date->diffInDays($borrow->due_date) }}
                                                    days)
                                                </span>
                                            @else
                                                <span class="text-xs py-1 px-2 rounded-full font-bold bg-green-600 text-green-100">
                                                    Returned On Time
                                                </span>
                                            @endif
                                        @else
                                            @if ($borrow->due_date->isPast())
                                                <span class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                                    Overdue ({{ $borrow->due_date->diffForHumans() }})
                                                </span>
                                            @else
                                                <span class="text-xs py-1 px-2 rounded-full font-bold bg-yellow-600 text-yellow-100">
                                                    Borrowed
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($borrow->status === 'returned')
                                            <a href="{{ route('borrow-return.return.form', ['book' => $borrow->book, 'borrow' => $borrow]) }}"
                                                class="text-primary hover:underline text-sm">
                                                Edit Return
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-2 text-center text-gray-500 italic">
                                        No borrow history available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
