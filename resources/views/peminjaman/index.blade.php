{{-- filepath: resources/views/peminjaman/index.blade.php --}}
<x-app-layout>
 <!-- Session Status Messages -->
 @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg relative bg-green-100 border border-green-300 text-green-800" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-lg relative bg-red-100 border border-red-300 text-red-800" role="alert">
        <span class="block sm:inline">{{ $errors->first() }}</span>
    </div>
@endif
@if(session('error'))
    <div class="mb-4 px-4 py-3 rounded-lg relative bg-red-100 border border-red-300 text-red-800" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif
    <div class="p-4 sm:p-6 lg:p-8 font-sans">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 items-stretch">
    <!-- Left Column: Header and Mencari Siswa -->
    <div class="sm:col-span-2 flex flex-col justify-between">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-5xl font-extrabold text-[#493628] ">Transaksi Peminjaman Buku</h1>
        </div>
        <!-- Card 1: Mencari Siswa -->
        <div class="shadow-lg rounded-2xl overflow-hidden">
            <div class="bg-thead p-4">
                <p class="font-semibold text-white">Mencari Siswa</p>
            </div>
            <div class="bg-activeBg p-6">
                @if(request()->isMethod('get') && request()->has('query_nis') && empty(request('query_nis')))
                    <div class="mb-4 px-4 py-2 rounded bg-red-100 text-red-800 border border-red-300">
                        NIS siswa tidak boleh kosong.
                    </div>
                @endif
                <form method="GET" class="flex items-center gap-4">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </div>
                        <input type="text" name="query_nis" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent" placeholder="Username/NIS" value="{{ $query_nis }}">
                    </div>
                    <button class="ml-3 px-14 inline-block bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white  font-bold py-2 rounded-full hover:opacity-90 transition" type="submit">CARI</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Right Column: Logo (row-span-2) -->
    <div class="hidden sm:flex flex-col items-center justify-center row-span-2">
        <img src="{{ asset('storage/assets/bukus.png') }}" alt="Decorative books icon" class="w-64 h-auto">
    </div>
</div>

        @if($siswa)
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            
            <!-- Left Column: Informasi Siswa -->
            <div class="lg:col-span-2">
                <div class="shadow-lg rounded-2xl overflow-hidden h-full flex flex-col">
                    <div class="bg-thead p-4">
                        <p class="font-semibold text-white">Informasi Siswa</p>
                    </div>
                    <div class="bg-activeBg p-6 flex-grow flex flex-col items-center">
                        <img src="{{asset('storage/assets/logo.png')}}" alt="Siswa Avatar" class="rounded-full w-32 mb-4 ">
                        
                        <div class="w-full space-y-3 text-sm">
                            <div>
                                <label class="font-medium text-accentDark">Nama Siswa</label>
                                <div class="mt-1 p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700">{{ $siswa->nama_siswa }}</div>
                            </div>
                            <div>
                                <label class="font-medium text-accentDark">NIS</label>
                                <div class="mt-1 p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700">{{ $siswa->nis }}</div>
                            </div>
                            <div>
                                <label class="font-medium text-accentDark">Kelas</label>
                                <div class="mt-1 p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700">{{ $siswa->kelas }}</div>
                            </div>
                             <div>
                                <label class="font-medium text-accentDark">Pinjaman Saat Ini</label>
                                <div class="mt-1 p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700">{{ $active_peminjaman->count() }}/2</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Mencari Buku & Keranjang -->
            <div class="lg:col-span-3 space-y-8">
                @if($active_peminjaman->count() < 2)
                <div class="shadow-lg rounded-2xl overflow-hidden">
                    <div class="bg-thead p-4"><p class="font-semibold text-white">Mencari Buku</p></div>
                    <div class="bg-activeBg p-6">
                        <form method="GET" class="flex items-center gap-4">
                            <input type="hidden" name="query_nis" value="{{ $query_nis }}">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                </div>
                                <input type="text" name="query_eksemplar" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent" placeholder="Nomor Eksemplar Buku">
                            </div>
                            <button class="ml-3 px-14 inline-block bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white  font-bold py-2 rounded-full hover:opacity-90 transition" type="submit">CARI</button>
                        </form>
                    </div>
                </div>
                @else
                    <div class="p-4 text-center bg-yellow-100 border border-yellow-300 text-yellow-800 rounded-2xl">Siswa sudah mencapai maksimum 2 pinjaman aktif.</div>
                @endif

                <!-- Keranjang Peminjaman -->
                 <div class="shadow-lg rounded-2xl overflow-hidden">
                    <div class="bg-thead p-4"><p class="font-semibold text-white">Keranjang Pinjaman</p></div>
                    <div class="bg-activeBg p-6">
                        <div class="space-y-2">
                             <!-- Header Row -->
                             <div class="hidden md:flex text-sm font-semibold text-accentDark px-4">
                                 <div class="w-2/5">Nomor Eksemplar</div>
                                 <div class="w-3/5">Judul Buku</div>
                             </div>
                            @forelse($keranjang as $item)
                                <div class="bg-gray-200 rounded-lg p-3 flex items-center">
                                    <div class="w-2/5 font-mono text-gray-800">{{ $item['nomor_eksemplar'] }}</div>
                                    <div class="w-3/5 text-gray-800">{{ $item['judul_buku'] }}</div>
                                    <div class="w-1/5 flex justify-end">
                                        <button form="delete-{{ $item['nomor_eksemplar'] }}" type="submit" class="p-2 text-red-500 hover:bg-red-100 rounded-full transition-colors">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Keranjang kosong.</p>
                            @endforelse
                        </div>
                    </div>
                 </div>
            </div>
        </div>
        
        <!-- Main Form for Processing Loan -->
        <form method="POST" action="{{ route('peminjaman.store') }}">
            @csrf
            @if($siswa) <input type="hidden" name="nis" value="{{ $siswa->nis }}"> @endif
            @foreach($keranjang as $item)
                <input type="hidden" name="nomor_eksemplar[]" value="{{ $item['nomor_eksemplar'] }}">
            @endforeach
            <div class="mt-8 flex justify-center">
                <button class="px-16 py-4 text-xl font-bold text-white bg-green-500 rounded-full shadow-lg hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all transform hover:scale-105" type="submit" @if(count($keranjang) == 0) disabled @endif>
                    Proseskan Peminjaman
                </button>
            </div>
        </form>

        <!-- Separate Delete Forms (hidden) -->
        @foreach($keranjang as $item)
            <form id="delete-{{ $item['nomor_eksemplar'] }}" method="GET" action="{{ route('peminjaman.index') }}" class="hidden">
                <input type="hidden" name="query_nis" value="{{ $query_nis }}">
                <input type="hidden" name="delete_eksemplar" value="{{ $item['nomor_eksemplar'] }}">
            </form>
        @endforeach

        @endif
    </div>
</x-app-layout>
