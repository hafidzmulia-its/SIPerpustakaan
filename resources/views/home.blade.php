{{-- resources/views/home.blade.php --}}
<x-app-layout>
    {{-- Alpine data for bukuDetail modal + carousels --}}
    <div class="space-y-8">
    

        {{-- Greeting & Search --}}
        <section class=" px-4">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Left Column: Greeting, Search, Sort -->
        <div class="flex-1 flex flex-col gap-6">
            <!-- Greeting -->
            <div>
                <h1 class="text-5xl font-extrabold text-[#493628]">
                    Haloo, {{ (Auth::user()->level_user === "Siswa") ? Auth::user()->siswa->nama_siswa : Auth::user()->username }}!
                </h1>
                <p class="text-2xl pt-3 font-medium text-[#493628]">{{ Auth::user()->level_user }}</p>
            </div>
            <!-- Search bar and Cari button -->
            <div>
                <div class="relative">
                    <img src="{{ asset('storage/assets/cari.png') }}" alt="Search Icon"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 w-8  text-gray-500 pointer-events-none">
                    <form method="GET" action="{{ url()->current() }}" class="flex">
                        <input
                            type="text"
                            name="search"
                            placeholder="Klik di sini untuk mencari buku ..."
                            value="{{ request('search') }}"
                            class="w-full bg-[#D2C2B5] text-[#493628] text-2xl font-medium  placeholder-[#493628] rounded-full pl-14 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#493628]"
                        >
                        <button
                            type="submit"
                            class="ml-3 px-20 inline-block bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white  font-bold py-2 rounded-full hover:opacity-90 transition"
                        >
                            CARI
                        </button>
                    </form>
                </div>
            </div>
            <!-- Sort filter -->
            <div>
                <form method="GET" action="{{ url()->current() }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div class="relative inline-block w-full md:w-1/3">
                        <img src="{{ asset('storage/assets/filter.png') }}" alt="Filter Icon"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 pointer-events-none">
                        <select
                            name="sort_by"
                            onchange="this.form.submit()"
                            class="appearance-none pl-10 pr-8 py-2 border border-[#493628] rounded-full bg-white text-[#493628] focus:outline-none focus:ring-2 focus:ring-[#493628] w-full"
                        >
                            <option value="" disabled selected>Sortir berdasarkan ...</option>
                            @foreach ($allowed as $col)
                                <option value="{{ $col }}" @selected(request('sort_by') == $col)>
                                    {{ ucfirst(str_replace('_', ' ', $col)) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-[#493628] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </form>
            </div>
        </div>
        <!-- Right Column: Top Right Image -->
        <div class="flex-shrink-0 flex items-start justify-start">
            {{-- You can replace this image with any relevant image --}}
            {{-- Replace with your image --}}
            <img src="{{ asset('storage/assets/bukus.png') }}" alt="Books" class="w-52 object-contain">
        </div>
    </div>
</section>
        @if ($search)
            <div class="px-4 space-y-4">
                <h3 class="text-lg font-semibold text-[#493628]">Hasil pencarian untuk: "{{ $search }}"</h3>
                @if($filteredBukus->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($filteredBukus as $buku)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}"
                                     class=" mx-auto h-64 max-w-40 object-cover rounded-lg shadow-md">
                                @if($buku->tersedia === 0)
                                    <div class="absolute inset-0 bg-black mx-auto bg-opacity-50 rounded-lg h-64 max-w-40"></div>
                                    <div class="absolute top-5 right-14 transform rotate-[40deg]">
                                        <span class="bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded shadow">
                                            Masih dipinjam
                                        </span>
                                    </div>
                                @endif
                                <div class="mt-2 text-center">
                                    <p class="text-sm font-medium text-[#493628]">{{ $buku->judul }}</p>
                                    <p class="text-xs text-[#493628]/80">Penulis: {{ $buku->pengarang }}</p>
                                    {{-- You can add more details or a Detail button --}}
                                    <button
                                            class="mt-2 text-xs text-white bg-[#493628] px-2 py-1 rounded"
                                            @click="bukuDetail.open(buku)"
                                            >Detail
                                        </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[#493628]">Tidak ada buku ditemukan.</p>
                @endif
            </div>
        @else
        {{-- Buku Terpopuler --}}
        <div x-data="{
            bukuDetail: {
                show: false,
                buku: {},
                open(b) { this.buku = b; this.show = true },
                close() { this.show = false }
            },
            // Carousels: popular and available
            popular: @js($popularChunks),       // array of arrays of book objects
            available: @js($availableChunks),   // likewise
            slidePopular: 0,
            slideAvailable: 0,
            nextSlide(type) {
                if (type === 'popular') {
                    this.slidePopular = this.slidePopular < this.popular.length - 1
                        ? this.slidePopular + 1
                        : 0;
                } else {
                    this.slideAvailable = this.slideAvailable < this.available.length - 1
                        ? this.slideAvailable + 1
                        : 0;
                }
            },
            prevSlide(type) {
                if (type === 'popular') {
                    this.slidePopular = this.slidePopular > 0
                        ? this.slidePopular - 1
                        : this.popular.length - 1;
                } else {
                    this.slideAvailable = this.slideAvailable > 0
                        ? this.slideAvailable - 1
                        : this.available.length - 1;
                }
            }
        }">
        <section class="px-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-[#493628]">Buku terpopuler</h2>
                <a href="{{ route('buku.popular') }}"
                   class="bg-[#D2C2B5] text-[#493628] px-3 py-1 rounded-full text-base hover:bg-[#C0B0A3] transition">
                    Lihat semua
                </a>
            </div>

            {{-- Carousel --}}
            <div class="mt-4" x-data>
                <div class="relative">
                    {{-- Slides --}}
                    <template x-for="(chunk, idx) in popular" :key="idx">
                        <div x-show="slidePopular === idx" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                            <template x-for="buku in chunk" :key="buku.kode_buku">
                                <div class="relative group">
                                    {{-- Book cover --}}
                                    <img :src="`/storage/${buku.cover}`"
                                         :alt="buku.judul"
                                         class="mx-auto max-w-40 h-64 object-cover rounded-lg shadow-md">
                                    {{-- If not tersedia (tersedia == 0): overlay and badge --}}
                                    <template x-if="buku.tersedia === 0">
                                        <div>
                                            {{-- Dark overlay --}}
                                            <div class="absolute inset-0 max-w-40 h-64 mx-auto bg-black bg-opacity-50 rounded-lg"></div>
                                            {{-- Badge --}}
                                            <div class="absolute top-4 right-6 transform rotate-[40deg]">
                                                <span class="bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded shadow">
                                                    Masih dipinjam
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                    {{-- Info under cover --}}
                                    <div class="mt-2 text-center">
                                        <p class="text-sm font-medium text-[#493628]" x-text="buku.judul"></p>
                                        <p class="text-xs text-[#493628]/80" x-text="buku.pengarang"></p>
                                        <button
                                            class="mt-2 text-xs text-white bg-[#493628] px-2 py-1 rounded"
                                            @click="bukuDetail.open(buku)"
                                            >Detail
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Prev/Next buttons --}}
                    <button
                        @click="prevSlide('popular')"
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-[#D2C2B5] text-[#493628] p-2 rounded-full shadow hover:bg-[#C0B0A3] transition"
                    >
                        {{-- Left arrow --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        @click="nextSlide('popular')"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-[#D2C2B5] text-[#493628] p-2 rounded-full shadow hover:bg-[#C0B0A3] transition"
                    >
                        {{-- Right arrow --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        {{-- Buku Tersedia --}}
        <section class="px-4 pt-12">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-[#493628]">Buku tersedia</h2>
                <a href="{{ route('buku.tersedia') }}"
                   class="bg-[#D2C2B5] text-[#493628] px-3 py-1 rounded-full text-base hover:bg-[#C0B0A3] transition">
                    Lihat semua
                </a>
            </div>

            {{-- Carousel --}}
            <div class="mt-4" x-data>
                <div class="relative">
                    {{-- Slides --}}
                    <template x-for="(chunk, idx) in available" :key="idx">
                        <div x-show="slideAvailable === idx" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                            <template x-for="buku in chunk" :key="buku.kode_buku">
                                <div class="relative group">
                                    {{-- Book cover --}}
                                    <img :src="`/storage/${buku.cover}`"
                                         :alt="buku.judul"
                                         class="h-64 max-w-44 object-cover rounded-lg shadow-md mx-auto">
                                    {{-- If available info needed: no overlay --}}
                                    <div class="mt-2 text-center">
                                        <p class="text-sm font-medium text-[#493628]" x-text="buku.judul"></p>
                                        <p class="text-xs text-[#493628]/80" x-text="buku.pengarang"></p>
                                        <p class="text-xs text-[#493628]/80" x-text="`Tersedia: ${buku.tersedia}`"></p>
                                        <button
                                            class="mt-2 text-xs text-white bg-[#493628] px-2 py-1 rounded"
                                            @click="bukuDetail.open(buku)"
                                            >Detail
                                        </button>

                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Prev/Next buttons --}}
                    <button
                        @click="prevSlide('available')"
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-[#D2C2B5] text-[#493628] p-2 rounded-full shadow hover:bg-[#C0B0A3] transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        @click="nextSlide('available')"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-[#D2C2B5] text-[#493628] p-2 rounded-full shadow hover:bg-[#C0B0A3] transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

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
    @endif
</x-app-layout>
