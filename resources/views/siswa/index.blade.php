{{-- filepath: resources/views/siswa/index.blade.php --}}
<x-app-layout>

    {{-- Main Content Area --}}
    <div class="p-4 sm:p-6 lg:p-8 font-sans text-accentDark">
        
        <div class="flex flex-col md:flex-row gap-8 mb-6">
            <!-- Left Column: Greeting, Search, Sort -->
            <div class="flex-1 flex flex-col gap-6">
                <!-- Greeting -->
                <div>
                <h1 class="text-4xl font-extrabold text-accentDark">Manajemen Siswa</h1>
                </div>
                <!-- Search bar and Cari button -->
                <form method="GET" action="{{ url()->current() }}">
                    <!-- Search bar and Cari button -->
                    <div class="flex items-center gap-3">
                        <div class="relative flex-grow">
                            <img src="{{ asset('storage/assets/cari.png') }}" alt="Search Icon"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-8 text-gray-500 pointer-events-none">
                            <input
                                type="text" name="query"
                                placeholder="Klik di sini untuk mencari NIS/Nama Siswa/Kelas..."
                                value="{{ $query }}"
                                class="w-full bg-[#D2C2B5] text-[#493628] text-xl font-medium placeholder-[#493628] rounded-full pl-14 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#493628]"
                            >
                        </div>
                        {{-- Hidden fields for sorting --}}
                        <input type="hidden" name="sort_by" value="{{ $sort_by }}">
                        <input type="hidden" name="sort_dir" value="{{ $sort_dir }}">
                        <button
                            type="submit"
                            class="px-12 inline-block bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white font-bold py-3 rounded-full hover:opacity-90 transition"
                        >
                            CARI
                        </button>
                    </div>

                    <!-- Actions and Filters Row -->
                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Add User Button -->
                        <button type="button" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 font-bold bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white rounded-lg shadow-md hover:opacity-90 transition-opacity" 
                                x-data @click="$dispatch('open-create')">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            TAMBAH SISWA
                        </button>
                        
                        <!-- Per Page Filter -->
                        <div class="flex items-center gap-2 text-accentDark">
                            <label for="per_page" class="font-medium">Tampilkan:</label>
                            <select name="per_page" id="per_page" class="bg-[#D2C2B5] text-[#493628] border-transparent rounded-full focus:ring-2 focus:ring-[#493628] text-sm py-2" onchange="this.form.submit()">
                                <option value="10" @selected($per_page == 10)>10</option>
                                <option value="25" @selected($per_page == 25)>25</option>
                                <option value="50" @selected($per_page == 50)>50</option>
                                <option value="1000" @selected($per_page == 1000)>Semua</option>
                            </select>
                            <span class="font-medium">baris</span>
                        </div>
                    </div>
                </form>
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

        
        <!-- Student Table Card -->
        <div class="shadow-lg rounded-2xl overflow-hidden">
            <div class="bg-thead p-4">
                <p class="font-semibold text-white">Daftar Siswa</p>
            </div>
            <div class="bg-activeBg py-2 sm:py-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-thead text-white">
                            <tr>
                                @php
                                    $columns = ['nis' => 'NIS', 'nama_siswa' => 'Nama Siswa', 'kelas' => 'Kelas'];
                                @endphp
                                @foreach($columns as $key => $title)
                                <th class="p-4 font-semibold">
                                    <a href="{{ route('siswa.index', array_merge(request()->all(), ['sort_by' => $key, 'sort_dir' => $sort_by == $key && $sort_dir == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-2">
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
                            @forelse($siswas as $siswa)
                                <tr class="border-b border-gray-300 {{ $loop->odd ? 'bg-sidebarBg' : 'bg-activeBg' }} text-accentDark">
                                    <td class="p-4">{{ $siswa->nis }}</td>
                                    <td class="p-4">{{ $siswa->nama_siswa }}</td>
                                    <td class="p-4">{{ $siswa->kelas }}</td>
                                    <td class="p-4 flex justify-end gap-3">
                                        {{-- Edit Button --}}
                                        <button class="p-2 bg-yellow-400 text-white rounded-md hover:bg-yellow-500 transition"
                                            x-data @click="$dispatch('open-edit', {{ $siswa->toJson() }})">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </button>
                                        {{-- Delete Button --}}
                                        <form method="POST" action="{{ route('siswa.destroy', $siswa) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition" type="submit">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center p-6 text-gray-500">Tidak ada data siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Links --}}
                <div class="p-4">
                    {{ $siswas->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Create --}}
<div x-data="{ open: false }" x-show="open" @open-create.window="open = true" @close-modal.window="open = false" @keydown.escape.window="open = false" style="display: none" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.away="open = false" class="shadow-lg rounded-2xl overflow-hidden w-full max-w-md">
        <div class="bg-thead p-4"><h3 class="font-bold text-white">Tambah Data Siswa</h3></div>
        <div class="bg-activeBg p-6">
            <form method="POST" action="{{ route('siswa.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="font-semibold text-accentDark">NIS</label>
                    <input type="text" name="nis" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                </div>
                <div>
                    <label class="font-semibold text-accentDark">Password Akun</label>
                    <input type="password" name="password" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                </div>
                <div>
                    <label class="font-semibold text-accentDark">Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                </div>
                <div>
                    <label class="font-semibold text-accentDark">Kelas</label>
                    <input type="text" name="kelas" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" required>
                </div>
                <div class="flex gap-4 justify-end pt-4">
                    <button class="px-6 py-2 font-semibold text-gray-700 bg-gray-300 rounded-lg hover:bg-gray-400 transition" type="button" @click="open = false">BATAL</button>
                    <button class="px-6 py-2 font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600 transition" type="submit">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- Modal Edit --}}
    {{-- Modal Edit --}}
<div x-data="{ open: false, siswa: {} }" x-show="open" @open-edit.window="open = true; siswa = $event.detail" @close-modal.window="open = false" @keydown.escape.window="open = false" style="display: none" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.away="open = false" class="shadow-lg rounded-2xl overflow-hidden w-full max-w-md">
        <div class="bg-thead p-4"><h3 class="font-bold text-white">Edit Data Siswa</h3></div>
        <div class="bg-activeBg p-6">
            <form method="POST" :action="'/siswa/' + siswa.nis" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="font-semibold text-accentDark">NIS</label>
                    <input type="text" name="nis" class="mt-1 w-full p-2 bg-gray-300 border border-gray-400 rounded-lg text-gray-500 cursor-not-allowed" x-model="siswa.nis" required readonly>
                </div>
                <div>
                    <label class="font-semibold text-accentDark">Password Akun (isi jika ingin ganti)</label>
                    <input type="password" name="password" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark">
                </div>
                <div>
                    <label class="font-semibold text-accentDark">Nama Siswa</label>
                    <input type="text" name="nama_siswa" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" x-model="siswa.nama_siswa" required>
                </div>
                <div>
                    <label class="font-semibold text-accentDark">Kelas</label>
                    <input type="text" name="kelas" class="mt-1 w-full p-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-accentDark" x-model="siswa.kelas" required>
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
