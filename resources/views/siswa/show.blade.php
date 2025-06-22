{{-- resources/views/siswa/show.blade.php --}}
<x-app-layout>
    {{-- Header slot --}}
    <div class="flex flex-col items-center justify-center text-center my-6 px-2">
        <h1 class="font-extrabold text-[#493628] text-[32px] md:text-[40px] leading-tight">
            Data Riwayat Peminjaman
        </h1>
        <p class="font-semibold text-[#493628]/80 text-[18px] md:text-[24px] mt-2">
            Data riwayat peminjaman buku di Perpustakaan Matematika ITS
        </p>
    </div>

    <div class="pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Search & Sort Form --}}
            <div class="bg-white rounded-lg pt-6 px-2 md:px-6">
                <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row sm:items-end gap-2 md:gap-4">
                    {{-- Search input --}}
                    <div class="relative flex-1 w-full">
                        <img src="{{ asset('storage/assets/cari.png') }}" alt="Search"
                             class="absolute left-3 top-1/2 transform -translate-y-1/2 w-7 md:w-8 pointer-events-none text-gray-500">
                        <input
                            type="text"
                            name="query"
                            value="{{ $query ?? '' }}"
                            placeholder="Klik di sini untuk mencari..."
                            class="w-full bg-sidebarBg text-[#493628] placeholder-[#493628] rounded-full pl-12 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-accentDark text-base md:text-lg"
                        >
                    </div>

                    {{-- Cari button --}}
                    <button
                        type="submit"
                        class="w-full sm:w-auto px-8 md:px-16 bg-gradient-to-r from-[#8C80AF] to-[#DFAEB1] text-white font-bold py-2 rounded-full hover:opacity-90 transition text-base md:text-lg"
                    >
                        CARI
                    </button>

                    {{-- Sort select --}}
                    <div class="relative w-full sm:w-auto">
                        <img src="{{ asset('storage/assets/filter.png') }}" alt="Filter"
                             class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 pointer-events-none text-gray-500">
                        <select
                            name="sort_by"
                            onchange="this.form.submit()"
                            class="appearance-none pl-10 pr-8 py-2 border border-accentDark rounded-full bg-white text-[#493628] focus:outline-none focus:ring-2 focus:ring-accentDark w-full sm:w-auto text-base md:text-lg"
                        >
                            <option value="" disabled {{ empty($sort_by) ? 'selected' : '' }}>Sortir berdasarkan ...</option>
                            @foreach ($allowed as $col)
                                <option value="{{ $col }}" @selected(($sort_by ?? '') == $col)>
                                    {{ ucfirst(str_replace('_', ' ', $col)) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-[#493628] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    {{-- Sort direction toggles --}}
                    <div class="flex space-x-2 w-full sm:w-auto justify-center sm:justify-start">
                        <button type="submit" name="sort_dir" value="asc"
                            class="w-10 h-10 flex items-center justify-center border border-accentDark rounded-full text-sm {{ ($sort_dir ?? '') == 'asc' ? 'font-semibold bg-accentDark text-white' : 'text-[#493628] hover:bg-accentDark hover:text-white' }}"
                            aria-label="Urutkan naik"
                        >
                            ▲
                        </button>
                        <button type="submit" name="sort_dir" value="desc"
                            class="w-10 h-10 flex items-center justify-center border border-accentDark rounded-full text-sm {{ ($sort_dir ?? '') == 'desc' ? 'font-semibold bg-accentDark text-white' : 'text-[#493628] hover:bg-accentDark hover:text-white' }}"
                            aria-label="Urutkan turun"
                        >
                            ▼
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow-sm rounded-2xl overflow-x-auto">
                <table class="table-auto w-full border-collapse text-sm md:text-base">
                    <thead>
                        <tr class="bg-accentDark text-white">
                            <th class="p-2 md:p-4 text-center">ID</th>
                            <th class="px-2 md:px-4 py-2 text-left">Judul Buku</th>
                            <th class="px-2 md:px-4 py-2 text-center">No. Eksemplar</th>
                            <th class="px-2 md:px-4 py-2 text-center">Tanggal Peminjaman</th>
                            <th class="px-2 md:px-4 py-2 text-center">Tanggal Pengembalian</th>
                            <th class="px-2 md:px-4 py-2 text-center">Jumlah Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $item)
                            <tr class="odd:bg-sidebarBg even:bg-activeBg">
                                <td class="px-2 md:px-4 py-2 text-center">{{ $item->id }}</td>
                                <td class="px-2 md:px-4 py-2 text-center">{{ $item->eksemplar->buku->judul ?? 'Data Buku telah dihapus' }}</td>
                                <td class="px-2 md:px-4 py-2 text-center">{{ $item->nomor_eksemplar }}</td>
                                <td class="px-2 md:px-4 py-2 text-center">{{ $item->tanggal_peminjaman ? \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d-m-Y') : '-' }}</td>
                                <td class="px-2 md:px-4 py-2 text-center">{{ $item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d-m-Y') : 'Belum Kembali' }}</td>
                                <td class="px-2 md:px-4 py-2 text-center"> {{ is_numeric($item->denda) ? 'Rp ' . number_format($item->denda, 0, ',', '.') : 'Rp -' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center text-[#493628]">
                                    Tidak ada riwayat peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>