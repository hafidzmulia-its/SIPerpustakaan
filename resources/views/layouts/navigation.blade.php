{{-- resources/views/layouts/navigation.blade.php --}}
@php
    $user = Auth::user();
@endphp

<nav class="fixed inset-0 flex flex-col items-center justify-center pb-24  w-32 h-screen ">
    {{-- Logo + Label --}}
    <div class="flex flex-col items-center justify-center h-20 space-y-1 mt-20">
        {{-- Logo image: width = 60px --}}
        <a href="{{ route('home') }}">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="Logo" class="w-[120px] h-auto object-contain">
        </a>
        {{-- Label under logo: app name or custom text --}}
        <span class="font-poppins font-medium text-[14px] text-[#493628]">
            {{ $user ? $user->level_user : 'Pengunjung'   }}
        </span>
    </div>

    {{-- Links container --}}
    <div class="flex-1 space-y-1  pt-32">
        @if($user && $user->level_user === 'Siswa')
            {{-- Katalog --}}
            <x-sidebar-link 
                :href="route('home')" 
                :active="request()->routeIs('home')"
                class="flex flex-col items-center justify-center w-full px-12"
            >
                <img src="{{ asset('storage/assets/katalog.png') }}" alt="Katalog" class="w-16 ">
                <span class="ml-2 text-[#493628]">Katalog</span>
            </x-sidebar-link>

            @if($user->siswa)
            {{-- Riwayat --}}
            <x-sidebar-link 
                :href="route('siswa.show', $user->siswa->nis)" 
                :active="request()->routeIs('siswa.show')"
                class="flex flex-col items-center justify-center w-full px-12"
            >
                <img src="{{ asset('storage/assets/riwayat.png') }}" alt="Riwayat" class="w-[58px] h-auto">
                <span class="ml-2 text-[#493628]">Riwayat</span>
            </x-sidebar-link>
            @endif

        @elseif($user && in_array($user->level_user, ['Petugas','Admin']))
            {{-- Peminjaman --}}
            <x-sidebar-link 
                :href="route('peminjaman.index')" 
                :active="request()->routeIs('peminjaman.*')"
                class="flex flex-col items-center justify-center w-full px-12"
            >
                <img src="{{ asset('storage/assets/peminjaman.png') }}" alt="Peminjaman" class="w-[86px]">
                <span class="ml-2">Peminjaman</span>
            </x-sidebar-link>

            {{-- Pengembalian --}}
            <x-sidebar-link 
                :href="route('pengembalian.index')" 
                :active="request()->routeIs('pengembalian.*')"
                class="flex flex-col items-center justify-center w-full px-12"
            >
                <img src="{{ asset('storage/assets/pengembalian.png') }}" alt="Pengembalian" class="w-[86px]">
                <span class="ml-2">Pengembalian</span>
            </x-sidebar-link>

            {{-- Manajemen Data (toggle submenu) --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open" class="w-full">

                    <div class="flex flex-col items-center pt-4 text-[#493628] justify-center w-full px-12 transition-colors 
                        {{ (request()->routeIs('siswa.*')||request()->routeIs('buku.*')||request()->routeIs('eksemplar.*')||request()->routeIs('users.*')) 
                            ? 'bg-[#EDE5DA] text-[#493628] shadow-md' 
                            : 'text-gray-800 hover:bg-[#EDE5DA]' 
                        }}">
                        <img src="{{ asset('storage/assets/masterdata.png') }}" alt="Manajemen" class="mx-auto w-16">
                        <span class="ml-2 text-sm">Manajemen <svg :class="open ? 'transform rotate-90' : ''" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg></span>
                        
                    </div>
                </button>
                <div x-show="open" x-cloak @click.away="open = false" class="absolute top-0 left-full ml-1 w-40 bg-white rounded-lg shadow-lg z-10">
                    <div class="flex flex-col py-1">
                        <x-sidebar-link :href="route('siswa.index')" :active="request()->routeIs('siswa.index')" class="px-3 py-2 text-sm">Siswa</x-sidebar-link>
                        <x-sidebar-link :href="route('buku.index')" :active="request()->routeIs('buku.*')" class="px-3 py-2 text-sm">Buku</x-sidebar-link>
                        <x-sidebar-link :href="route('eksemplar.index')" :active="request()->routeIs('eksemplar.*')" class="px-3 py-2 text-sm">Eksemplar</x-sidebar-link>
                        <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="px-3 py-2 text-sm">Petugas/Admin</x-sidebar-link>
                    </div>
                </div>
            </div>

            {{-- Laporan --}}
            <x-sidebar-link 
                :href="route('laporan')" 
                :active="request()->routeIs('laporan')"
                class="flex flex-col items-center justify-center w-full px-12"
            >
                <img src="{{ asset('storage/assets/laporan.png') }}" alt="Laporan" class="w-16">
                <span class="ml-2 text-[#493628]">Laporan</span>
            </x-sidebar-link>
        @endif
    </div>

    {{-- Logout with confirmation --}}
    <div class="pt-4 mb-4">
        <form x-ref="logoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="button" 
                @click.prevent="showLogoutModal = true"
                class="flex flex-col items-center justify-center  px-12 font-semibold text-[#493628] hover:bg-[#EDE5DA] w-full transition-colors"
            >
                <img src="{{ asset('storage/assets/logout.png') }}" alt="Keluar" class="w-14">
                <span class="ml-2">Keluar</span>
            </button>
        </form>
    </div>
</nav>
