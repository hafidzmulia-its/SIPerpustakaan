<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIPerpustakaan') }}</title>

    <!-- Load Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    
    {{-- Existing fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false, showLogoutModal: false }" class="flex h-screen bg-white">
        {{-- Mobile backdrop --}}
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden"
            x-cloak
        ></div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-20 w-32 bg-sidebarBg transform md:translate-x-0 transition-transform duration-200 flex flex-col"
        >
            @include('layouts.navigation')
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col md:ml-32">
            {{-- Mobile header --}}
            <header class="md:hidden flex items-center justify-between bg-accentDark text-white p-2 shadow">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 focus:outline-none">
                    <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="text-lg font-semibold">{{ config('app.name') }}</div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-auto md:px-20 md:pt-16 bg-white p-4">
                {{ $slot }}
            </main>
        </div>

        {{-- Logout Confirmation Modal --}}
        <div
            x-show="showLogoutModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white rounded-lg shadow-lg w-80 max-w-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h2>
                <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin keluar?</p>
                <div class="flex justify-end space-x-3">
                    <button
                        @click="showLogoutModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                    >
                        Batal
                    </button>
                    <button
                        @click="$refs.logoutForm.submit()"
                        class="px-4 py-2 bg-primaryBrown text-white rounded-md hover:bg-[#7d6f63]"
                    >
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>