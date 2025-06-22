{{-- filepath: resources/views/buku/index.blade.php --}}
<x-app-layout>

    {{-- Main Content Area --}}
    <div class="p-4 sm:p-6 lg:p-8 font-sans text-accentDark">
        
        <div class="flex flex-col md:flex-row gap-8 mb-6">
            <!-- Left Column: Greeting, Search, etc. -->
            <div class="flex-1 flex flex-col gap-6">
                <!-- Greeting -->
                <div>
                    <h1 class="text-4xl font-extrabold text-accentDark">Manajemen Master Data Buku</h1>
                </div>
                <!-- Search bar and Cari button -->
                <div>
                    <div class="relative">
                        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3">
                            <div class="relative flex-grow">
                                <img src="{{ asset('storage/assets/cari.png') }}" alt="Search Icon"
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 w-8 text-gray-500 pointer-events-none">
                                <input
                                    type="text" name="query"
                                    placeholder="Klik di sini untuk mencari buku ..."
                                    value="{{ $query }}"
                                    class="w-full bg-[#D2C2B5] text-[#493628] text-xl font-medium placeholder-[#493628] rounded-full pl-14 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#493628]"
                                >
                            </div>
                            {{-- Hidden fields to persist other query params --}}
                            <input type="hidden" name="per_page" value="{{ $per_page }}">
                            <input type="hidden" name="sort_by" value="{{ $sort_by }}">
                            <input type="hidden" name="sort_dir" value="{{ $sort_dir }}">
                            <button
                                type="submit"
                                class="px-12 inline-block bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white font-bold py-3 rounded-full hover:opacity-90 transition"
                            >
                                CARI
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Add Book Button -->
                <!-- Add Book Button & Filter -->
<div class="self-start flex flex-wrap gap-3 items-center">
    <button class="flex items-center justify-center gap-2 px-6 py-3 font-bold bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white rounded-lg shadow-md hover:opacity-90 transition-opacity"
        x-data @click="$dispatch('open-create')">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        TAMBAH BUKU
    </button>
    <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
        <input type="hidden" name="query" value="{{ $query }}">
        <input type="hidden" name="sort_by" value="{{ $sort_by }}">
        <input type="hidden" name="sort_dir" value="{{ $sort_dir }}">
        <label for="per_page" class="ml-2 text-[#493628] font-medium">Tampilkan</label>
        <select name="per_page" id="per_page" class="bg-[#D2C2B5] text-[#493628] rounded-full px-4 py-2 border border-[#493628] focus:ring-2 focus:ring-[#493628]" onchange="this.form.submit()">
            <option value="10" {{ $per_page == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ $per_page == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ $per_page == 50 ? 'selected' : '' }}>50</option>
            <option value="1000" {{ $per_page == 1000 ? 'selected' : '' }}>Semua</option>
        </select>
        <span class="ml-1 text-[#493628]">baris</span>
    </form>
</div>
                
                
            </div>
            <!-- Right Column: Top Right Image -->
            <div class="hidden md:flex flex-shrink-0 items-start justify-end">
                <img src="{{ asset('storage/assets/bukus.png') }}" alt="Books" class="w-52 object-contain">
            </div>
        </div>
       

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
        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg relative bg-red-100 border border-red-300 text-red-800" role="alert">
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
        @endif

        
        <!-- Book Table Card -->
        <div class="shadow-lg rounded-2xl overflow-hidden">
            <div class="bg-thead p-4">
                <p class="font-semibold text-white">Daftar Buku</p>
            </div>
            <div class="bg-activeBg pt-2 sm:pt-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-thead text-white">
                            <tr>
                                @php
                                    $columns = ['kode_buku' => 'Kode Buku', 'judul' => 'Judul', 'pengarang' => 'Pengarang', 'tahun_terbit' => 'Tahun'];
                                @endphp
                                @foreach($columns as $key => $title)
                                <th class="p-4 font-semibold">
                                    <a href="{{ route('buku.index', array_merge(request()->all(), ['sort_by' => $key, 'sort_dir' => $sort_by == $key && $sort_dir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-2">
                                        {{ $title }}
                                        @if($sort_by == $key)
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                @if($sort_dir == 'asc')
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                @endif
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                @endforeach
                                <th class="p-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukus as $buku)
                                <tr class="border-b border-gray-300 {{ $loop->odd ? 'bg-sidebarBg' : 'bg-activeBg' }} text-accentDark">
                                    <td class="p-4">{{ $buku->kode_buku }}</td>
                                    <td class="p-4">{{ $buku->judul }}</td>
                                    <td class="p-4">{{ $buku->pengarang }}</td>
                                    <td class="p-4">{{ $buku->tahun_terbit }}</td>
                                    <td class="p-4 flex justify-end gap-3">
                                        <button class="p-2 bg-yellow-400 text-white rounded-md hover:bg-yellow-500 transition"
                                            x-data @click="$dispatch('open-edit', {{ $buku->toJson() }})">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </button>
                                        <form method="POST" action="{{ route('buku.destroy', $buku) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition" type="submit">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center p-6 text-gray-500">Tidak ada data buku.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $bukus->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Create --}}
    <div x-data="{ open: false, coverUrl: null }" x-show="open" @open-create.window="open = true" @close-modal.window="open = false" @keydown.escape.window="open = false" style="display: none" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="open = false" class="shadow-lg rounded-2xl overflow-hidden w-full max-w-md">
            <div class="bg-thead p-4"><h3 class="font-bold text-white">Tambah Data Buku</h3></div>
            <div class="bg-activeBg p-6">
                <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold text-accentDark">Kode Buku</label>
                            <input type="text" name="kode_buku" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                        </div>
                        <div>
                            <label class="font-semibold text-accentDark">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-accentDark">Judul</label>
                        <input type="text" name="judul" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                    </div>
                    <div>
                        <label class="font-semibold text-accentDark">Pengarang</label>
                        <input type="text" name="pengarang" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                    </div>
                    <div>
                        <label class="font-semibold text-accentDark">Cover Buku</label>
                        <input type="file" name="cover" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" accept="image/*" @change="coverUrl = URL.createObjectURL($event.target.files[0])">
                        <template x-if="coverUrl">
                            <img :src="coverUrl" alt="Preview Cover" class="mt-4 w-24 h-36 object-cover rounded-lg shadow-md">
                        </template>
                    </div>
                    <div class="flex gap-4 justify-end pt-4">
                        <button class="px-6 py-2 font-semibold text-gray-700 bg-gray-300 rounded-lg hover:bg-gray-400 transition" type="button" @click="open = false; coverUrl = null;">BATAL</button>
                        <button class="px-6 py-2 font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600 transition" type="submit">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-data="{ open: false, buku: {}, coverUrl: null }" x-show="open" @open-edit.window="open = true; buku = $event.detail; coverUrl = buku.cover ? `{{ asset('storage') }}/${buku.cover}` : null" @close-modal.window="open = false" @keydown.escape.window="open = false" style="display: none" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="open = false" class="shadow-lg rounded-2xl overflow-hidden w-full max-w-md">
            <div class="bg-thead p-4"><h3 class="font-bold text-white">Edit Data Buku</h3></div>
            <div class="bg-activeBg p-6">
                <form method="POST" :action="`/buku/${buku.kode_buku}`" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold text-accentDark">Kode Buku</label>
                            <input type="text" name="kode_buku" class="mt-1 w-full p-2 bg-gray-300 border border-gray-400 rounded-lg text-gray-500 cursor-not-allowed" x-model="buku.kode_buku" required readonly>
                        </div>
                        <div>
                            <label class="font-semibold text-accentDark">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" x-model="buku.tahun_terbit" required>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-accentDark">Judul</label>
                        <input type="text" name="judul" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" x-model="buku.judul" required>
                    </div>
                    <div>
                        <label class="font-semibold text-accentDark">Pengarang</label>
                        <input type="text" name="pengarang" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" x-model="buku.pengarang" required>
                    </div>
                    <div>
                        <label class="font-semibold text-accentDark">Cover Baru (Opsional)</label>
                        <input type="file" name="cover" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" accept="image/*" @change="coverUrl = URL.createObjectURL($event.target.files[0])">
                        <template x-if="coverUrl">
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Preview:</p>
                                <img :src="coverUrl" alt="Preview Cover" class="mt-2 w-24 h-36 object-cover rounded-lg shadow-md">
                            </div>
                        </template>
                    </div>
                    <div class="flex gap-4 justify-end pt-4">
                        <button class="px-6 py-2 font-semibold text-gray-700 bg-gray-300 rounded-lg hover:bg-gray-400 transition" type="button" @click="open = false">BATAL</button>
                        <button class="px-6 py-2 font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600 transition" type="submit">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
