{{-- resources/views/layouts/navigation.blade.php --}}
@php
    $user = Auth::user();
@endphp

<nav class="fixed inset-0 flex flex-col w-32 h-screen">
    {{-- Logo Section: 1/8 of height --}}
    <div class="flex flex-col items-center justify-center h-1/5 space-y-1">
        {{-- Logo image --}}
        <a href="{{ route('home') }}">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="w-[120px] h-auto object-contain">
        </a>
        {{-- User level label --}}
        <span class="font-poppins font-medium text-[14px] text-[#493628]">
            {{ $user ? $user->level_user : 'Pengunjung' }}
        </span>
    </div>

    {{-- Navigation Links: 6/8 (3/4) of height --}}
    <div class="flex-1 flex flex-col items-center justify-center space-y-1 py-4">
        @if($user && $user->level_user === 'Siswa')
            {{-- Katalog --}}
            <x-sidebar-link 
                :href="route('home')" 
                :active="request()->routeIs('home')"
                class="flex flex-col items-center justify-center w-full px-4"
            >
                <img src="{{ asset('storage/assets/katalog.png') }}" alt="Katalog" class="w-16">
                <span class="text-[#493628]">Katalog</span>
            </x-sidebar-link>

            @if($user->siswa)
            {{-- Riwayat --}}
            <x-sidebar-link 
                :href="route('siswa.show', $user->siswa->nis)" 
                :active="request()->routeIs('siswa.show')"
                class="flex flex-col items-center justify-center w-full px-4"
            >
                <img src="{{ asset('storage/assets/riwayat.png') }}" alt="Riwayat" class="w-[58px] h-auto">
                <span class="text-[#493628]">Riwayat</span>
            </x-sidebar-link>
            @endif

        @elseif($user && in_array($user->level_user, ['Petugas','Admin']))
            {{-- Peminjaman --}}
            <x-sidebar-link 
                :href="route('peminjaman.index')" 
                :active="request()->routeIs('peminjaman.*')"
                class="flex flex-col items-center justify-center w-full px-4"
            >
                <img src="{{ asset('storage/assets/peminjaman.png') }}" alt="Peminjaman" class="w-16">
                <span class="text-[#493628]">Peminjaman</span>
            </x-sidebar-link>

            {{-- Pengembalian --}}
            <x-sidebar-link 
                :href="route('pengembalian.index')" 
                :active="request()->routeIs('pengembalian.*')"
                class="flex flex-col items-center justify-center w-full px-4"
            >
                <img src="{{ asset('storage/assets/pengembalian.png') }}" alt="Pengembalian" class="w-16">
                <span class="text-[#493628]">Pengembalian</span>
            </x-sidebar-link>

            @if(Auth::user()->level_user === 'Petugas')
            <x-sidebar-link 
                :href="route('siswa.index')" 
                :active="request()->routeIs('siswa.*')"
                class="flex flex-col items-center justify-center w-full px-4"
            >
                <img src="{{ asset('storage/assets/masterdata.png') }}" alt="Manajemen" class="w-16">
                <span class="text-[#493628]">Manajemen</span>
            </x-sidebar-link>
            @endif

            @if(Auth::user()->level_user === 'Admin')
            {{-- Manajemen Data (toggle submenu) --}}
            <div x-data="{ open: false }" class="relative w-full">
                <button type="button" @click="open = !open" class="w-full">
                    <div class="flex flex-col items-center justify-center w-full px-4 py-2 transition-colors 
                        {{ (request()->routeIs('siswa.*')||request()->routeIs('buku.*')||request()->routeIs('eksemplar.*')||request()->routeIs('users.*')) 
                            ? 'bg-[#EDE5DA] text-[#493628] shadow-md rounded-lg' 
                            : 'text-[#493628] hover:bg-[#EDE5DA] rounded-lg' 
                        }}">
                        <img src="{{ asset('storage/assets/masterdata.png') }}" alt="Manajemen" class="w-16">
                        <span class="flex items-center gap-1 text-sm">
                            Manajemen
                            <svg :class="open ? 'transform rotate-90' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </button>
                <div x-show="open" x-cloak @click.away="open = false" class="absolute top-0 left-full ml-2 w-40 bg-white rounded-lg shadow-lg z-10">
                    <div class="flex flex-col py-1">
                        <x-sidebar-link :href="route('siswa.index')" :active="request()->routeIs('siswa.index')" class="px-3 py-2 text-sm">Siswa</x-sidebar-link>
                        <x-sidebar-link :href="route('buku.index')" :active="request()->routeIs('buku.*')" class="px-3 py-2 text-sm">Buku</x-sidebar-link>
                        <x-sidebar-link :href="route('eksemplar.index')" :active="request()->routeIs('eksemplar.*')" class="px-3 py-2 text-sm">Eksemplar</x-sidebar-link>
                        <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="px-3 py-2 text-sm">Petugas/Admin</x-sidebar-link>
                    </div>
                </div>
            </div>
            @endif

            {{-- Laporan --}}
            <x-sidebar-link 
                :href="route('laporan')" 
                :active="request()->routeIs('laporan')"
                class="flex flex-col items-center justify-center w-full px-4"
            >
                <img src="{{ asset('storage/assets/laporan.png') }}" alt="Laporan" class="w-16">
                <span class="text-[#493628]">Laporan</span>
            </x-sidebar-link>
        @endif
    </div>

    {{-- Logout Section: 1/8 of height --}}
    <div class="flex items-center justify-center h-1/5">
        <form x-ref="logoutForm" method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button 
                type="button" 
                @click.prevent="showLogoutModal = true"
                class="flex flex-col items-center justify-center w-full px-4 py-2 font-semibold text-[#493628] hover:bg-[#EDE5DA] rounded-lg transition-colors"
            >
                <img src="{{ asset('storage/assets/logout.png') }}" alt="Keluar" class="w-14">
                <span>Keluar</span>
            </button>
        </form>
    </div>
</nav>