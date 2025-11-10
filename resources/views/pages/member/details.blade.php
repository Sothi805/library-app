<x-app-layout>
    <x-slot name="title">Member Details</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h1 class="text-xl font-semibold">Member Details</h1>
        </div>
    </x-slot>

    <div class="p-8 space-y-6">

        {{-- ACTION BUTTONS --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('members.edit', $member) }}">
                <x-primary-button>Edit Member</x-primary-button>
            </a>

            <a href="{{ route('members.index') }}">
                <x-secondary-button>Back to Member List</x-secondary-button>
            </a>
        </div>

        {{-- MEMBER INFORMATION --}}
        <div class="bg-white/10 backdrop-blur-sm p-6 shadow rounded-md">
            <h2 class="text-lg font-bold mb-4">Member Information</h2>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <h3 class="text-gray-600 text-sm">Member ID</h3>
                    <h2 class="text-base font-semibold">{{ $member->member_code }}</h2>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm">Full Name</h3>
                    <h2 class="text-base font-semibold">
                        {{ trim(($member->first_name ?? '') . ' ' . ($member->middle_name ?? '') . ' ' . ($member->last_name ?? '')) }}
                    </h2>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm">Gender</h3>
                    <h2 class="text-base font-semibold capitalize">{{ $member->gender }}</h2>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm">Email</h3>
                    <h2 class="text-base font-semibold">{{ $member->email ?? '-' }}</h2>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm">Phone</h3>
                    <h2 class="text-base font-semibold">{{ $member->phone ?? '-' }}</h2>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm">Status</h3>
                    @if ($member->status === 'active')
                        <span class="text-xs py-1 px-2 rounded-full bg-green-600 text-green-100 font-bold">Active</span>
                    @else
                        <span class="text-xs py-1 px-2 rounded-full bg-red-600 text-red-100 font-bold">
                            Inactive since {{ $member->inactive_since->format('M d, Y') }}
                        </span>
                    @endif
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm">Member Since</h3>
                    <h2 class="text-base font-semibold">{{ $member->created_at->format('M d, Y') }}</h2>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm">Added by</h3>
                    <h2 class="text-base font-semibold">{{ $member->snapshot_added_by }}</h2>
                </div>

                @if ($member->updated_by)
                    <div>
                        <h3 class="text-gray-600 text-sm">Last Updated</h3>
                        <h2 class="text-base font-semibold">
                            {{ $member->updated_at->format('M d, Y') }} by {{ $member->snapshot_updated_by }}
                        </h2>
                    </div>
                @endif
            </div>
        </div>

        {{-- CURRENTLY BORROWED --}}
        <div class="bg-white/10 backdrop-blur-sm p-6 shadow rounded-md space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold">Currently Borrowed Books</h2>

                {{-- Per Page Selector --}}
                @if($activeBorrows->total() > 0)
                <form method="GET" action="{{ route('members.show', $member) }}" class="flex items-center space-x-2">
                    <label for="per_page" class="text-sm text-gray-600">Show:</label>
                    <select id="per_page" name="per_page"
                        class="px-2 py-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                        onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
                @endif
            </div>

            @if ($activeBorrows->total() > 0)
                <div class="overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-primary text-white uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2 text-center">Book Code</th>
                                <th class="px-4 py-2">Book Title</th>
                                <th class="px-4 py-2 text-center">Borrowed</th>
                                <th class="px-4 py-2 text-center">Due</th>
                                <th class="px-4 py-2 text-center">Condition (Before)</th>
                                <th class="px-4 py-2 text-center">Status</th>
                                <th class="px-4 py-2 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($activeBorrows as $borrow)
                                <tr class="hover:bg-gray-50/20">
                                    <td class="px-4 py-2 text-center font-medium">{{ $borrow->book->book_id }}</td>
                                    <td class="px-4 py-2">{{ $borrow->book->title }}</td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->borrowed_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->due_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center font-semibold">
                                        {{ $borrow->condition_before }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($borrow->due_date->isPast())
                                            <span class="text-xs py-1 px-2 rounded-full bg-red-600 text-red-100 font-bold">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="text-xs py-1 px-2 rounded-full bg-green-600 text-green-100 font-bold">
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="{{ route('borrow-return.return.form', ['book' => $borrow->book, 'borrow' => $borrow]) }}"
                                            class="text-primary font-semibold hover:underline">Return Book</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $activeBorrows->links() }}
                </div>
            @else
                <p class="text-gray-500 italic">No active borrows</p>
            @endif
        </div>

        {{-- BORROW HISTORY --}}
        <div class="bg-white/10 backdrop-blur-sm p-6 shadow rounded-md space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold">Borrow History</h2>

                {{-- Per Page Selector --}}
                @if($borrowHistory->total() > 0)
                <form method="GET" action="{{ route('members.show', $member) }}" class="flex items-center space-x-2">
                    <label for="per_page" class="text-sm text-gray-600">Show:</label>
                    <select id="per_page" name="per_page"
                        class="px-2 py-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                        onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
                @endif
            </div>

            @if ($borrowHistory->total() > 0)
                <div class="overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-primary text-white uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2 text-center">Book Code</th>
                                <th class="px-4 py-2">Book Title</th>
                                <th class="px-4 py-2 text-center">Borrowed</th>
                                <th class="px-4 py-2 text-center">Due</th>
                                <th class="px-4 py-2 text-center">Returned</th>
                                <th class="px-4 py-2 text-center">Condition</th>
                                <th class="px-4 py-2 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($borrowHistory as $borrow)
                                <tr class="hover:bg-gray-50/20">
                                    <td class="px-4 py-2 text-center">{{ $borrow->book->book_id }}</td>
                                    <td class="px-4 py-2">{{ $borrow->book->title }}</td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->borrowed_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center">{{ $borrow->due_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        {{ $borrow->returned_date ? $borrow->returned_date->format('M d, Y') : '-' }}
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        Before: <strong>{{ $borrow->condition_before }}</strong><br>
                                        After: <strong>{{ $borrow->condition_after ?? '-' }}</strong>
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        @if ($borrow->status === 'returned')
                                            @if ($borrow->returned_date->gt($borrow->due_date))
                                                <span class="text-xs py-1 px-2 rounded-full bg-red-600 text-red-100 font-bold">
                                                    Returned Late
                                                </span>
                                            @else
                                                <span class="text-xs py-1 px-2 rounded-full bg-green-600 text-green-100 font-bold">
                                                    Returned On Time
                                                </span>
                                            @endif
                                        @else
                                            @if ($borrow->due_date->isPast())
                                                <span class="text-xs py-1 px-2 rounded-full bg-red-600 text-red-100 font-bold">Overdue</span>
                                            @else
                                                <span class="text-xs py-1 px-2 rounded-full bg-yellow-600 text-yellow-100 font-bold">Borrowed</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $borrowHistory->links() }}
                </div>
            @else
                <p class="text-gray-500 italic">No borrow history available</p>
            @endif
        </div>

        {{-- DELETE MEMBER --}}
        <div class="mt-8">
            <div class="p-6 bg-white/10 backdrop-blur-sm shadow rounded-md">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-red-600">Danger Zone</h2>
                        <p class="text-sm text-gray-500">Once deleted, all member data will be permanently removed.</p>
                    </div>

                    <div>
                        @if ($member->borrows()->where('status', 'borrowed')->exists())
                            <x-danger-button disabled>Cannot Delete (Active Borrows)</x-danger-button>
                        @else
                            <div x-data="{ showDeleteModal: false }">
                                <x-danger-button @click="showDeleteModal = true">Delete Member</x-danger-button>

                                @include('pages.member.partials.delete-modal', [
                                    'member' => $member
                                ])
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>

    </div>
</x-app-layout>
