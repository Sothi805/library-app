<x-app-layout>
    <x-slot name="title">
        Members
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h1 class="text-xl font-semibold">Members</h1>

        </div>
    </x-slot>

    <div class="p-8 space-y-6">
        <div class="flex justify-between items-center">
            <div></div>
            <a href="{{ route('members.create') }}">
                <x-primary-button>
                    Add Member
                </x-primary-button>
            </a>
        </div>
        {{-- Member List --}}
        <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20">
            <table class="w-full table-auto text-sm text-left text-text-light-primary">
                <thead class="bg-primary text-white uppercase text-xs">
                    <tr>
                        <th scope="col" class="px-4 py-2 text-center rounded-tl-lg">Member ID</th>
                        <th scope="col" class="px-4 py-2">Name</th>
                        <th scope="col" class="px-4 py-2">Gender</th>
                        <th scope="col" class="px-4 py-2">Contact</th>
                        <th scope="col" class="px-4 py-2 text-center">Status</th>
                        <th scope="col" class="px-4 py-2 text-center">Active Borrows</th>
                        <th scope="col" class="px-4 py-2 text-center rounded-tr-lg">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-border-dark/10">
                    @forelse($members as $member)
                        <tr class="bg-background-light hover:bg-tertiary/10 transition-colors">
                            <td class="px-4 py-2 text-center font-medium">{{ $member->member_code }}</td>
                            <td class="px-4 py-2">
                                <div class="font-medium">
                                    {{ trim(($member->first_name ?? '') . ' ' . ($member->middle_name ?? '') . ' ' . ($member->last_name ?? '')) }}
                                </div>
                                @if($member->email)
                                    <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-2 capitalize">{{ $member->gender }}</td>
                            <td class="px-4 py-2">{{ $member->phone ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">
                                @php
                                    $activeBorrows = $member->borrows->where('status', 'borrowed');
                                    $hasOverdue = $activeBorrows->contains(function($borrow) {
                                        return $borrow->due_date->isPast();
                                    });
                                @endphp

                                @if($activeBorrows->count() > 0)
                                    @if($hasOverdue)
                                        <span class="text-xs py-1 px-2 rounded-full font-bold bg-red-600 text-red-100">
                                            Overdue
                                        </span>
                                    @else
                                        <span class="text-xs py-1 px-2 rounded-full font-bold bg-yellow-600 text-yellow-100">
                                            Borrowing
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs py-1 px-2 rounded-full font-bold bg-green-600 text-green-100">
                                        Available
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span class="font-medium">
                                    {{ $member->borrows->count() }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('members.show', $member) }}" class="hover:underline">
                                    details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-500 italic">
                                No members found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
