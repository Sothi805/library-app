<nav class="bg-white shadow border-r border-gray-200 w-64 min-h-screen max-h-screen sticky left-0 top-0 flex flex-col">

    <!-- Logo -->
    <div class="flex items-center justify-center h-18 border-b border-gray-100">
        <a href="{{ route('dashboard') }}">
            <img class="px-4 w-auto" src={{ asset('long-logo.png') }} alt="">
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 pt-4 space-y-2 overflow-y-auto">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-nav-link>

        <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')">
            Book Inventory
        </x-nav-link>

        {{-- Add more nav items here --}}
        {{-- Example:
        <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')">
            Books
        </x-nav-link>
        --}}
    </div>

    <!-- Profile + Logout Section -->
    <div class="">
        <a class="flex items-center space-x-3 px-4 py-2 border-b border-gray-100 hover:bg-blue-100"
            href="{{ route('profile.edit') }}">
            <!-- User Initial (Circle) -->
            <div
                class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-700 text-white font-semibold text-lg uppercase">
                {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
            </div>

            <!-- User Name -->
            <div class="flex flex-col">
                <h1><span class="font-medium text-sm text-gray-800">{{ Auth::user()->first_name }}</span>
                <span class="font-medium text-sm text-gray-800">{{ Auth::user()->middle_name ?? "" }}</span>
                <span class="font-medium text-sm text-gray-800">{{ Auth::user()->last_name }}</span></h1>
                <span class="text-xs text-gray-500">{{ Auth::user()->email }}</span>
            </div>
        </a>


        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full cursor-pointer text-left p-4 text-sm text-red-600 rounded-md hover:bg-red-50 transition">
                Log Out
            </button>
        </form>
    </div>
</nav>
