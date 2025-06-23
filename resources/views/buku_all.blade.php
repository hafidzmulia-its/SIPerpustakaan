{{-- filepath: resources/views/buku/lihat-semua.blade.php --}}
<x-app-layout>
    {{-- 
        Alpine.js data store for the page.
        It only initializes the `bukuDetail` modal functionality.
        The controller should pass the main data: $title, $bukus (paginated), $allowed (for sorting).
    --}}
    <div x-data="{
        bukuDetail: {
            show: false,
            buku: {},
            open(b) { this.buku = b; this.show = true },
            close() { this.show = false }
        }
    }">

        {{-- Main Content Area --}}
        <div class="p-4 sm:p-6 lg:p-8 font-sans text-accentDark">
            
            <div class="flex flex-col md:flex-row gap-8 mb-6">
                <!-- Left Column: Greeting, Search, etc. -->
                <div class="flex-1 flex flex-col gap-6">
                    <!-- Page Title (passed from controller) -->
                    <div>
                        <h1 class="text-4xl font-extrabold text-accentDark">{{ $title ?? 'Katalog Buku' }}</h1>
                    </div>
                    
                    <form method="GET" action="{{ url()->current() }}">
                        <!-- Search bar -->
                        <div class="flex items-center gap-3">
                            <div class="relative flex-grow">
                                <img src="{{ asset('storage/assets/cari.png') }}" alt="Search Icon"
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 w-8 text-gray-500 pointer-events-none">
                                <input
                                    type="text" name="search"
                                    placeholder="Klik di sini untuk mencari buku ..."
                                    value="{{ request('search') }}"
                                    class="w-full bg-[#D2C2B5] text-[#493628] text-xl font-medium placeholder-[#493628] rounded-full pl-14 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#493628]"
                                >
                            </div>
                            <button
                                type="submit"
                                class="px-12 inline-block bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white font-bold py-3 rounded-full hover:opacity-90 transition"
                            >
                                CARI
                            </button>
                        </div>

                        <!-- Sort filter -->
                        <div class="mt-4">
                             <div class="relative inline-block w-full md:w-1/3">
                                <img src="{{ asset('storage/assets/filter.png') }}" alt="Filter Icon"
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 pointer-events-none">
                                <select
                                    name="sort_by"
                                    onchange="this.form.submit()"
                                    class="appearance-none pl-10 pr-8 py-2 border border-[#493628] rounded-full bg-white text-[#493628] focus:outline-none focus:ring-2 focus:ring-[#493628] w-full"
                                >
                                    <option value="" disabled @selected(!request('sort_by'))>Sortir berdasarkan ...</option>

                                    @foreach ($allowed as $col)
    <option value="{{ $col }}" @selected(request('sort_by', 'kode_buku') == $col)>
        {{ $col === 'peminjaman' ? 'Paling Banyak Dipinjam' : ucfirst(str_replace('_', ' ', $col)) }}
    </option>
@endforeach
                                </select>
                                <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-[#493628] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                         <input type="hidden" name="search" value="{{ request('search') }}">
                    </form>
                </div>
                <!-- Right Column: Top Right Image -->
                <div class="hidden md:flex flex-shrink-0 items-start justify-end">
                    <img src="{{ asset('storage/assets/bukus.png') }}" alt="Books" class="w-52 object-contain">
                </div>
            </div>
           
            <!-- Book Grid -->
            <div class="mt-8">
                @if($bukus->count())
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-x-4 gap-y-8">
                        @foreach($bukus as $buku)
                            <div class="relative group text-center">
                                <!-- Book Cover -->
                                <img src="{{ $buku->cover ? asset('storage/' . $buku->cover) : 'https://placehold.co/300x450/D2C2B5/FFFFFF?text=No+Cover' }}" 
                                     alt="{{ $buku->judul }}"
                                     class="mx-auto h-64 w-44 object-cover rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300">
                                
                                <!-- 'Not Available' Badge -->
                                @if(isset($buku->tersedia) && $buku->tersedia === 0)
                                    <div class="absolute inset-x-0 top-0 h-64 max-w-[176px] mx-auto bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                        <span class="bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                                            Dipinjam
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Book Info -->
                                <div class="mt-4">
                                    <p class="text-sm font-bold text-accentDark truncate" title="{{ $buku->judul }}">{{ $buku->judul }}</p>
                                    <p class="text-xs text-accentDark/80">
                                        {{ $buku->pengarang }}
                                    </p>
                                    <button
                                        class="mt-2 text-xs text-white bg-sidebarBg px-4 py-1.5 rounded-full hover:opacity-80 transition"
                                        @click="bukuDetail.open({{ json_encode($buku) }})">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-12">
                        {{ $bukus->appends(request()->query())->links('pagination::tailwind') }}
                    </div>

                @else
                    <div class="text-center py-16">
                        <p class="text-lg text-gray-500">Tidak ada buku yang ditemukan.</p>
                        <a href="{{ url()->current() }}" class="mt-4 inline-block text-blue-500 hover:underline">Hapus filter dan coba lagi</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Modal for Buku Detail --}}
        <div
            x-show="bukuDetail.show"
            x-cloak
            @keydown.escape.window="bukuDetail.close()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4"
        >
            <!-- Modal Card -->
            <div 
                @click.away="bukuDetail.close()"
                class="shadow-lg rounded-2xl overflow-hidden w-full max-w-2xl bg-activeBg text-accentDark transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <!-- Modal Header -->
                <div class="bg-thead p-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white" x-text="bukuDetail.buku.judul"></h3>
                    <button class="p-1 rounded-full text-white hover:bg-white/20 transition-colors" @click="bukuDetail.close()">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Left Column: Book Cover -->
                    <div class="md:col-span-1">
                        <img :src="bukuDetail.buku.cover ? `/storage/${bukuDetail.buku.cover}` : 'https://placehold.co/300x450/D2C2B5/FFFFFF?text=No+Cover'"
                             :alt="bukuDetail.buku.judul"
                             class="w-full h-auto object-cover rounded-lg shadow-xl border-4 border-white"
                             onerror="this.onerror=null;this.src='https://placehold.co/300x450/D2C2B5/FFFFFF?text=No+Cover';">
                    </div>

                    <!-- Right Column: Book Details -->
                    <div class="md:col-span-2 flex flex-col space-y-4">
                        <div>
                            <span class="font-mono text-sm bg-gray-200 text-gray-700 px-3 py-1 rounded-full" x-text="bukuDetail.buku.kode_buku"></span>
                        </div>
                        
                        <div>
                            <p class="font-semibold">Pengarang</p>
                            <p class="text-gray-700" x-text="bukuDetail.buku.pengarang"></p>
                        </div>
                        
                        <div>
                            <p class="font-semibold">Tahun Terbit</p>
                            <p class="text-gray-700" x-text="bukuDetail.buku.tahun_terbit"></p>
                        </div>

                        <!-- Stats Section -->
                        <div class="pt-4 border-t border-gray-300 flex items-center gap-6">
                            <template x-if="bukuDetail.buku.tersedia !== undefined">
                                <div class="text-center">
                                    <p class="text-2xl font-black" x-text="bukuDetail.buku.tersedia"></p>
                                    <p class="text-sm font-medium">Tersedia</p>
                                </div>
                            </template>
                            <template x-if="bukuDetail.buku.total_peminjam !== undefined">
                                 <div class="text-center">
                                    <p class="text-2xl font-black" x-text="bukuDetail.buku.total_peminjam"></p>
                                    <p class="text-sm font-medium">Dipinjam</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
