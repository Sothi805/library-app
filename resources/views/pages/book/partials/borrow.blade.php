<x-app-layout>
    <x-slot name="title">Borrow Book</x-slot>

    <x-slot name="header">
        <h1 class="text-xl font-bold">Borrow Book</h1>
    </x-slot>

    <div class="p-8 space-y-4">

        <form method="POST" action="{{ route('books.borrow', $book) }}"
              class="p-6 bg-white shadow-sm sm:rounded-lg space-y-6">
            @csrf

            <div class="flex justify-between items-center mb-2">
                <h1 class="font-bold text-base">Borrow Information</h1>

                <a href="{{ route('books.show', $book) }}">
                    <x-secondary-button>Go Back</x-secondary-button>
                </a>
            </div>

            <div class="space-y-4">

                {{-- SELECT MEMBER --}}
                <div>
                    <x-input-label for="member_id" value="Select Member" />
                    <select name="member_id" id="member_id"
                        class="w-full border-gray-300 rounded-md"
                        required>
                        <option value="" selected disabled>-- Choose Member --</option>
                        @foreach (\App\Models\Member::orderBy('member_code')->get() as $member)
                            <option value="{{ $member->id }}">
                                {{ $member->member_code }} - {{ $member->first_name }} {{ $member->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CONDITION BEFORE BORROW --}}
                <div>
                    <x-input-label for="condition_before" value="Book Condition (When Borrowing)" />
                    <select id="condition_before" name="condition_before"
                        class="w-full border-gray-300 rounded-md" required>
                        <option value="">-- Select Condition --</option>
                        <option value="As New">As New</option>
                        <option value="Good">Good</option>
                        <option value="Worn">Worn</option>
                        <option value="Damaged">Damaged</option>
                    </select>
                </div>

                {{-- BORROW DATE (Today) --}}
                <div>
                    <x-input-label value="Borrow Date" />
                    <input type="text" value="{{ now()->format('M d, Y') }}" class="w-full border-gray-300 rounded-md bg-gray-100" readonly>
                    <p class="mt-1 text-sm text-gray-500">Book will be due on {{ now()->addDays(14)->format('M d, Y') }}</p>
                </div>

            </div>

            <div class="flex justify-end">
                <x-primary-button>Confirm Borrow</x-primary-button>
            </div>

        </form>

    </div>
</x-app-layout>
