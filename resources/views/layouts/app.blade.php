<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-cyan-50 via-blue-50 to-orange-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gradient-to-r from-cyan-400 to-blue-400 backdrop-blur border-b border-cyan-200 shadow-lg">
                    <div class="container mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8 text-white">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Global flash messages -->
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                @if(session('success'))
                    <div class="mb-4 rounded-xl border-2 border-emerald-300 bg-emerald-50 text-emerald-800 px-4 py-3 flex items-center gap-2 shadow-md">
                        <span class="text-xl">✅</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-xl border-2 border-orange-300 bg-orange-50 text-orange-800 px-4 py-3 flex items-center gap-2 shadow-md">
                        <span class="text-xl">⚠️</span> {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="mt-12 border-t-2 border-cyan-200 bg-gradient-to-r from-cyan-400 to-blue-400 text-white">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-sm">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-3">
                        <p>
                            ☀️ &copy; {{ date('Y') }} {{ config('app.name') }} - Summer Edition 🏝️
                        </p>
                        <div class="flex items-center gap-4 text-white">
                            <a href="https://tailwindcss.com/docs" target="_blank" class="hover:text-white/70">Tailwind Docs</a>
                            <a href="https://flowbite.com/docs/getting-started/introduction/" target="_blank" class="hover:text-white/70">Flowbite</a>
                            <a href="https://daisyui.com/components/" target="_blank" class="hover:text-white/70">daisyUI</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
