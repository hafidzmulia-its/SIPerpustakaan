<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 font-sans">
        <!-- Session Status Messages -->
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg relative bg-green-100 border border-green-300 text-green-800" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded-lg relative bg-red-100 border border-red-300 text-red-800" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 items-stretch">
            <!-- Left Column: Header and Mencari Siswa -->
            <div class="sm:col-span-2 flex flex-col justify-between">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-5xl font-extrabold text-[#493628] ">Transaksi Pengembalian Buku</h1>
                </div>
                <!-- Card 1: Mencari Siswa -->
                <div class="shadow-lg rounded-2xl overflow-hidden">
                    <div class="bg-thead p-4">
                        <p class="font-semibold text-white">Mencari Siswa</p>
                    </div>
                    <div class="bg-activeBg p-6">
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
        <form method="POST" action="{{ route('pengembalian.store') }}">
            @csrf
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
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Column: Daftar Peminjaman & Denda -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Daftar Peminjaman Buku Card -->
                    <div class="shadow-lg rounded-2xl overflow-hidden">
                        <div class="bg-thead p-4"><p class="font-semibold text-white">Daftar Peminjaman Buku</p></div>
                        <div class="bg-activeBg p-6">
                            <div class="space-y-2">
                                <!-- Header Row -->
                                <div class="hidden md:flex text-sm font-semibold text-accentDark px-4 py-2">
                                    <div class="w-1/12"></div>
                                    <div class="w-3/12">Peminjaman ID</div>
                                    <div class="w-4/12">Judul Buku</div>
                                    <div class="w-4/12">Tgl Pinjam</div>
                                </div>
                                @forelse($active_peminjaman as $pinjam)
                                    <div class="bg-gray-200 rounded-lg p-3 flex items-center text-sm">
                                        <div class="w-1/12 flex items-center justify-center">
                                            <input  type="checkbox"
                                                    name="pengembalian[]"
                                                    value="{{ $pinjam->id }}"
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded denda-checkbox"
                                                    data-denda="{{ $pinjam->denda }}">
                                        </div>
                                        <div class="w-3/12 font-mono text-gray-800">{{ $pinjam->id }}</div>
                                        <div class="w-4/12 text-gray-800">{{ $pinjam->eksemplar->buku->judul ?? '-' }}</div>
                                        <div class="w-4/12 text-gray-800">{{ \Carbon\Carbon::parse($pinjam->tanggal_peminjaman)->format('Y-m-d') }}</div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 py-4">Tidak ada buku yang sedang dipinjam.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!-- Total Denda Card -->
                    <div class="shadow-lg rounded-2xl overflow-hidden">
                        <div class="bg-thead p-4"><p class="font-semibold text-white">Total Denda</p></div>
                        <div class="bg-activeBg p-6 text-center">
                             <p class="text-sm text-accentDark mb-2">Total denda yang harus dibayarkan dari peminjaman buku terakhir sebesar:</p>
                             <p class="text-4xl font-black text-accentDark mb-4">
                                Rp<span id="total-denda">{{ number_format($total_denda, 0, ',', '.') }}</span>
                            </p>
                             <div class="flex justify-around items-center pt-4 border-t border-gray-300">
                                 <div>
                                     <p class="text-sm text-accentDark">Jumlah buku yang dipinjam</p>
                                     <p class="text-2xl font-extrabold text-accentDark">{{ $active_peminjaman->count() }}</p>
                                 </div>
                                 <div>
                                     <p class="text-sm text-accentDark">Keterlambatan (hari)</p>
                                     <p class="text-2xl font-extrabold text-accentDark">{{ $total_keterlambatan }}</p>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Submit Button -->
            <div class="mt-8 flex justify-center">
                <button class="px-16 py-4 text-xl font-bold text-white bg-blue-600 rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all transform hover:scale-105" type="submit" @if($active_peminjaman->count() == 0) disabled @endif>
                    Proseskan Pengembalian
                </button>
            </div>
        </form>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateTotalDenda() {
                let total = 0;
                document.querySelectorAll('.denda-checkbox:checked').forEach(function(cb) {
                    total += parseInt(cb.dataset.denda, 10) || 0;
                });
                document.getElementById('total-denda').textContent = total.toLocaleString('id-ID');
            }

            document.querySelectorAll('.denda-checkbox').forEach(function(cb) {
                cb.addEventListener('change', updateTotalDenda);
            });

            // Optionally, update on page load if some are pre-checked
            updateTotalDenda();
        });
        </script>
</x-app-layout>