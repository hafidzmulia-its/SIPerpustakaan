<x-app-layout>
    <style>
        .bar-gradient-1 { background-image: linear-gradient(to top, #005C97, #363795); }
        .bar-gradient-2 { background-image: linear-gradient(to top, #1E9600, #2E8B57); }
        .bar-gradient-3 { background-image: linear-gradient(to top, #FFD700, #FBB03B); }
        .bar-gradient-4 { background-image: linear-gradient(to top, #4A90E2, #50C9C3); }
        .bar-gradient-5 { background-image: linear-gradient(to top, #D31027, #EA384D); }
    </style>

    <div class="p-4 sm:p-6 lg:p-8 font-sans">
        <!-- Header Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Left Column: Header and Filter -->
    <div class="sm:col-span-2 flex flex-col justify-between">
        <!-- Header -->
        <div class="mb-12 pt-8">
            <h1 class="text-5xl font-extrabold text-[#493628]">
                Laporan Perpustakaan <span class="text-[#9A816F]">LITERASIK</span>
            </h1>
        </div>
        <!-- Filter Form -->
        <form method="GET" class="mb-2 max-w-xs">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0l-3.75-3.75M17.25 21L21 17.25" />
                    </svg>
                </div>
                <select name="filter" id="filter" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 bg-white rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-[#9A816F] focus:border-transparent appearance-none" onchange="this.form.submit()">
                    <option value="" disabled @selected($filter=='')>Sortir berdasarkan...</option>
                    <option value="hari" @selected($filter=='hari')>Hari Ini</option>
                    <option value="bulan" @selected($filter=='bulan')>Bulan Ini</option>
                    <option value="tahun" @selected($filter=='tahun')>Tahun Ini</option>
                </select>
            </div>
        </form>
    </div>
    <!-- Right Column: Logo (row-span-2) -->
    <div class="hidden sm:flex flex-col items-center justify-center row-span-2">
        <img src="{{ asset('storage/assets/bukus.png') }}" alt="Decorative books icon" class="w-42 h-auto">
    </div>
</div>

        <!-- Summary Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">

            <!-- Total Peminjaman Card -->
            <div class="shadow-lg rounded-2xl overflow-hidden flex flex-col bg-white">
                <!-- Header: Uses 'sidebarBg' for the background -->
                <div class="bg-thead p-4 text-center">
                    <p class="font-semibold text-white ">Total Peminjaman</p>
                </div>
                <!-- Body: Uses 'activeBg' for the background and 'accentDark' for text -->
                <div class="bg-activeBg p-8 text-center text-accentDark flex-grow flex flex-col justify-center">
                    <p class="text-7xl font-extrabold text-black leading-none">{{ $total_peminjaman }}</p>
                    <p class="text-2xl font-extrabold mt-2 text-black">Buku</p>
                </div>
            </div>

            <!-- Pendapatan Denda Card -->
            <div class="shadow-lg rounded-2xl overflow-hidden flex flex-col bg-white">
                <!-- Header -->
                <div class="bg-thead p-4 text-center">
                    <p class="font-semibold text-white">Pendapatan Denda</p>
                </div>
                <!-- Body -->
                <div class="bg-activeBg p-8 text-center text-accentDark flex-grow flex flex-col justify-center">
                    <p class="text-7xl font-extrabold text-black leading-none">{{ number_format($total_denda, 0, ',', '.') }}</p>
                    <p class="text-2xl font-extrabold mt-2 text-black">Rupiah</p>
                </div>
            </div>

            <!-- Anggota Aktif Card -->
            <div class="shadow-lg rounded-2xl overflow-hidden flex flex-col bg-white">
                <!-- Header -->
                <div class="bg-thead p-4 text-center">
                    <p class="font-semibold text-white">Anggota Aktif</p>
                </div>
                <!-- Body -->
                <div class="bg-activeBg p-8 text-center text-accentDark flex-grow flex flex-col justify-center">
                    <p class="text-7xl text-black font-extrabold leading-none">{{ $anggota_aktif }}</p>
                    <p class="text-2xl font-extrabold text-black mt-2">Siswa</p>
                </div>
            </div>

        </div>


        <!-- Top 5 Books Chart Section -->
        <div class="bg-activeBg  rounded-2xl shadow-lg mb-24">
            <div class="bg-thead p-4 text-center rounded-t-2xl">
                <p class="font-semibold text-white">Top 5 Buku Terpopuler</p>
            </div>
            <div class="pt-4">
                    
                    @if($top_buku->isNotEmpty())
                        <div class="overflow-x-auto">
                            <div class="flex justify-center items-end gap-4 sm:gap-6 md:gap-12 px-2 md:px-8" style="height: 500px;">
                                @php
                                    $max = $top_buku->max('total') ?: 1;
                                    $barGradients = ['bar-gradient-1', 'bar-gradient-2', 'bar-gradient-3', 'bar-gradient-4', 'bar-gradient-5'];
                                    $minBarHeight = 15; // percent, or use px if you prefer
                                @endphp

                                @foreach($top_buku as $index => $buku)
                                @php
                                    // Calculate bar height: min 15%, max 100%
                                    $percent = $max > 1 ? (($buku['total'] / $max) * (100 - $minBarHeight)) + $minBarHeight : 100;
                                @endphp
                                <div class="flex flex-col items-center h-full w-[120px] sm:w-1/5 max-w-[180px]">
                                    <!-- Bar and total count -->
                                    <div class="w-full flex-grow flex flex-col justify-end items-center">
                                        <p class="font-bold text-gray-700 text-lg sm:text-2xl md:text-3xl mb-2">{{ $buku['total'] }}</p>
                                        <div class="w-2/5 sm:w-3/5 rounded-t-lg shadow-inner {{ $barGradients[$index % 5] }}"
                                            style="height: {{ $percent }}%;">
                                        </div>
                                    </div>

                                        <!-- Book Cover and Rank -->
                                        <div class="relative mt-[-50px] sm:mt-[-70px] z-10">
                                            <!-- Rank Circle -->
                                            <div class="absolute -top-3 -left-3 sm:-top-4 sm:-left-4 w-8 h-8 sm:w-12 sm:h-12 bg-[#E4C7C7] rounded-full flex items-center justify-center text-gray-800 font-bold text-base sm:text-lg shadow-md">
                                                {{ $index + 1 }}
                                            </div>
                                            <!-- Book Cover -->
                                            @if($buku['cover'])
                                                <img src="{{ asset('storage/' . $buku['cover']) }}" alt="{{ $buku['judul'] }}" class="w-[90px] h-[135px] sm:w-[180px] sm:h-[270px] object-cover shadow-lg">
                                            @else
                                                <div class="w-[90px] h-[135px] sm:w-[180px] sm:h-[270px] bg-gray-300 shadow-lg flex items-center justify-center text-white text-center p-2">No Cover</div>
                                            @endif
                                        </div>

                                        <!-- Book Details -->
                                        <div class="text-center mt-2 sm:mt-4 h-12 sm:h-16 flex flex-col justify-center">
                                            <p class="font-bold text-xs sm:text-base md:text-lg text-gray-800 leading-tight truncate" title="{{ $buku['judul'] ?? 'Data telah dihapus' }}">
                                                {{ \Illuminate\Support\Str::limit($buku['judul'] ?? 'Data telah dihapus', 25) }}
                                            </p>
                                            <p class="text-[10px] sm:text-sm md:text-base text-gray-600 truncate" title="{{ $buku['pengarang'] }}">
                                                {{ \Illuminate\Support\Str::limit($buku['pengarang'], 24) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-12">Data buku terpopuler tidak tersedia.</p>
                    @endif
                </div>
            </div>
        </div>

</x-app-layout>