<x-app-layout>
    <x-slot name="title">
        Borrow Book
    </x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h1 class="text-xl font-semibold">Borrow Book</h1>
        </div>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Book Info Card --}}
        <div class="flex gap-4 bg-white/10 backdrop-blur-sm shadow p-4">
            @if ($book->cover_path)
                <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Book Cover"
                    class="w-24 h-32 object-cover rounded">
            @else
                <div class="w-24 h-32 bg-gray-200 flex items-center justify-center rounded">
                    No Cover
                </div>
            @endif
            <div class="flex flex-col justify-between flex-1">
                <div>
                    <h2 class="text-lg font-bold">{{ $book->title }}</h2>
                    <p class="text-sm text-gray-600">Book ID: {{ $book->book_id }}</p>
                    <p class="text-sm text-gray-600">Available Copies: {{ $book->available_copies }}</p>
                    @if ($book->author)
                        <p class="text-sm text-gray-600">Author: {{ $book->author }}</p>
                    @endif
                    @if ($book->category)
                        <p class="text-sm text-gray-600">Category: {{ $book->category->name }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Borrow Form --}}
        <form action="{{ route('borrow-return.borrow', $book) }}" method="POST"
            class="bg-white/10 backdrop-blur-sm p-6 rounded-md shadow" x-data="{
                search: '',
                showDropdown: false,
                selectedMember: null,
                members: {{ $members->map(function ($m) {
                        return [
                            'id' => $m->id,
                            'code' => $m->member_code,
                            'name' => trim(($m->first_name ?? '') . ' ' . ($m->middle_name ?? '') . ' ' . ($m->last_name ?? '')),
                        ];
                    })->toJson() }},
                get filteredMembers() {
                    if (this.search === '') return this.members;
                    return this.members.filter(member =>
                        member.code.toLowerCase().includes(this.search.toLowerCase()) ||
                        member.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                selectMember(member) {
                    this.selectedMember = member;
                    this.search = member.code + ' - ' + member.name;
                    this.showDropdown = false;
                }
            }">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <x-input-label for="member_search" value="Select Member" />
                    <input type="text" id="member_search" x-model="search" @focus="showDropdown = true"
                        @click.away="showDropdown = false"
                        class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                        placeholder="Search by member code or name" autocomplete="off" required />
                    <input type="hidden" name="member_id" x-model="selectedMember?.id" required />

                    {{-- Dropdown --}}
                    <div x-show="showDropdown && filteredMembers.length > 0" x-cloak
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                        <template x-for="member in filteredMembers" :key="member.id">
                            <div @click="selectMember(member)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                <div class="font-semibold text-sm" x-text="member.code"></div>
                                <div class="text-xs text-gray-600" x-text="member.name"></div>
                            </div>
                        </template>
                    </div>

                    <x-input-error :messages="$errors->get('member_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="condition_before" value="Book Condition" />
                    <select id="condition_before" name="condition_before"
                        class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                        required>
                        <option value="" selected disabled>Select condition</option>
                        <option value="As new">As new</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Poor">Poor</option>
                        <option value="Damaged">Damaged</option>
                    </select>
                    <x-input-error :messages="$errors->get('condition_before')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="borrowed_date" value="Borrow Date" />
                    <input type="date" id="borrowed_date" name="borrowed_date"
                        class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                        value="{{ old('borrowed_date', date('Y-m-d')) }}" required />
                    <x-input-error :messages="$errors->get('borrowed_date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="borrow_days" value="Borrow Duration (Days)" />
                    <select id="borrow_days" name="borrow_days"
                        class="mt-1 px-2 py-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                        required>
                        <option value="" selected disabled>Select duration</option>
                        <option value="7">7 days (1 week)</option>
                        <option value="14" selected>14 days (2 weeks)</option>
                        <option value="21">21 days (3 weeks)</option>
                        <option value="30">30 days (1 month)</option>
                    </select>
                    <x-input-error :messages="$errors->get('borrow_days')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 space-x-4">
                <a href="{{ url()->previous() }}">
                    <x-secondary-button type="button">
                        Cancel
                    </x-secondary-button>
                </a>

                <x-primary-button>
                    Borrow Book
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
