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
            <h1 class="text-3xl font-bold">Welcome, <span class="text-primary">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span> !!</h1>
            <p class="text-base text-gray-600">Here's an overview of your library's activities.</p>
        </div>
        {{-- Welcome End --}}

        <div x-data="{
            selected: 'Last 30 Days',
            options: ['Last 7 Days', 'Last 30 Days', 'Last 90 Days', 'Last 180 Days', 'Last 365 Days', 'All Time'],

            stats: {
                'Last 7 Days': {
                    books: { total: 11000, borrowed: 800, returned: 600, overdue: 50, lost: 5 },
                    members: { total: 320, active: 100, inactive: 220, avgBooks: 1.9 }
                },
                'Last 30 Days': {
                    books: { total: 12456, borrowed: 891, returned: 800, overdue: 91, lost: 10 },
                    members: { total: 350, active: 127, inactive: 223, avgBooks: 2.5 }
                },
                'Last 90 Days': {
                    books: { total: 15000, borrowed: 1500, returned: 1300, overdue: 120, lost: 15 },
                    members: { total: 400, active: 150, inactive: 250, avgBooks: 2.9 }
                },
                'Last 180 Days': {
                    books: { total: 17000, borrowed: 2100, returned: 1900, overdue: 130, lost: 20 },
                    members: { total: 430, active: 200, inactive: 230, avgBooks: 3.2 }
                },
                'Last 365 Days': {
                    books: { total: 20000, borrowed: 3100, returned: 2900, overdue: 180, lost: 25 },
                    members: { total: 470, active: 250, inactive: 220, avgBooks: 3.8 }
                },
                'All Time': {
                    books: { total: 24000, borrowed: 4000, returned: 3900, overdue: 210, lost: 30 },
                    members: { total: 500, active: 300, inactive: 200, avgBooks: 4.0 }
                },
            }
        }" class="space-y-4">
            {{-- Dropdown --}}
            <div class="relative inline-block">
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-1.5 bg-white/10 backdrop-blur-sm border border-gray-300 rounded-md text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            <span x-text="selected"></span>
                            <span class="material-symbols-outlined ml-1 text-gray-500 text-base">
                                keyboard_arrow_down
                            </span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <template x-for="option in options" :key="option">
                            <button @click="selected = option" type="button"
                                class="block w-full cursor-pointer text-left px-4 py-2 text-sm transition hover:bg-tertiary/10 hover:text-primary"
                                :class="{ 'bg-tertiary/10 text-primary font-semibold': selected === option }">
                                <span x-text="option"></span>
                            </button>
                        </template>
                    </x-slot>
                </x-dropdown>
            </div>


            {{-- Book Cards --}}
            <div class="cards grid grid-cols-5 gap-4 mb-4">
                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-primary font-semibold">Total Books</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].books.total"></h1>
                    <p class="text-xs text-green-600 font-semibold">+ 120 <span x-text="selected"></span></p>
                </div>

                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-secondary font-semibold">Borrowed Books</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].books.borrowed"></h1>
                    <p class="text-xs text-green-600 font-semibold">+100 <span x-text="selected"></span></p>
                </div>

                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-yellow-600 font-semibold">Returned Books</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].books.returned"></h1>
                    <p class="text-xs text-green-600 font-semibold">89.8% Returned</p>
                </div>

                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-accent font-semibold">Overdue Books</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].books.overdue"></h1>
                    <p class="text-xs text-red-600 font-semibold">Action Required</p>
                </div>

                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-gray-600 font-semibold">Lost/Damaged Books</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].books.lost"></h1>
                    <p class="text-xs text-red-600 font-semibold">Updated for <span x-text="selected"></span></p>
                </div>
            </div>

            {{-- Member Cards --}}
            <div class="cards grid grid-cols-4 gap-4">
                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-primary font-semibold">Total Members</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].members.total"></h1>
                    <p class="text-xs text-green-600 font-semibold">+1.2% this period</p>
                </div>

                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-primary font-semibold">Active Members</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].members.active"></h1>
                    <p class="text-xs text-green-600 font-semibold">Active in <span x-text="selected"></span></p>
                </div>

                <div class="card space-y-2 p-4 bg-white shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-accent font-semibold">Inactive Members</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].members.inactive"></h1>
                    <p class="text-xs text-red-600 font-semibold">No activity this period</p>
                </div>

                <div class="card space-y-2 p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h4 class="text-xs text-gray-600 font-semibold">Average Books per Member</h4>
                    <h1 class="text-xl font-bold" x-text="stats[selected].members.avgBooks"></h1>
                    <p class="text-xs text-green-600 font-semibold">Compared to last period</p>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-3 gap-4 mt-8">
            {{-- Most Popular Book Start --}}
            <div class="col-span-2 space-y-2 p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="font-bold text-base">Top 10 Most Popular Books</h1>
                <div class="overflow-x-auto rounded-lg shadow-sm border border-border-dark/20 bg-background-light">
                    <table class="w-full table-fixed text-sm text-left text-text-light-primary">
                        <thead class="bg-primary text-white uppercase text-xs">
                            <tr>
                                <th scope="col" class="px-6 py-2 rounded-tl-lg w-[7.5%]">Rank</th>
                                <th scope="col" class="px-6 py-2 w-[15%]">Book ID</th>
                                <th scope="col" class="px-6 py-2 w-[37.5%]">Book Title</th>
                                <th scope="col" class="px-6 py-2 w-[25%]">Borrowed (Times)</th>
                                <th scope="col" class="px-6 py-2 rounded-tr-lg text-center w-[15%]">Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-border-dark/10">
                            @for ($i = 1; $i <= 10; $i++)
                                <tr class="hover:bg-tertiary/10 transition-colors">
                                    <td class="px-6 py-2">{{ $i }}</td>
                                    <th class="px-6 py-2">E0000{{ $i }}</th>
                                    <td class="px-6 py-2">
                                        <div
                                            class="font-semibold text-sm line-clamp-1 w-full overflow-hidden text-ellipsis whitespace-nowrap">
                                            Battle Through the Heavens: Rise of the Flame Emperor
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 text-center">230</td>
                                    <td class="px-6 py-2 text-center"><a class="hover:underline"
                                            href="">details</a></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Most Popular Book End --}}

            {{-- Most Recent Borrowed Book Start --}}
            <div class="p-4 bg-white space-y-2 overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="font-bold text-base">Most Recent Borrowed Books</h1>
                @for ($i = 1; $i <= 9; $i++)
                    <div class="border rounded px-2 border-border-dark/20 bg-background-light ">
                        <h2 class="font-semibold text-sm line-clamp-1 text-primary">Battle Through the heavens</h2>
                        <h3 class="text-xs text-gray-600">01/11/2025</h3>
                    </div>
                @endfor
            </div>
        </div>
        {{-- Most Recent Borrowed Book Start --}}
    </div>

</x-app-layout>
