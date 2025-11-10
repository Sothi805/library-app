<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('logo.png') }}" />

    <title>
        PSIS{{ isset($title) ? ' - ' . $title : '' }}
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Google Material Symbols Outlined --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    {{-- x-cloak --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex bg-background-light">
        @include('layouts.navigation')

        <div class="flex flex-col w-full">
            @isset($header)
                <header class="bg-white/10 backdrop-blur-sm shadow sticky top-0 z-40">
                    <div class="h-18 flex items-center px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1">
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>
    </div>

    {{-- ✅ Teleport target for all modals --}}
    <div id="modal-root"></div>

    @stack('modals')
</body>


</html>
