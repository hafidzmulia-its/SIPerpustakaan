{{-- resources/views/buku_all.blade.php --}}



<h3>{{ $title }}</h3>
<div class="row">
    @foreach ($bukus as $buku)
        <div class="col-md-2">
            <div class="card mb-3">
                <img src="{{ asset('storage/' . $buku->cover) }}" class="img-fluid rounded-start" alt="{{ $buku->judul }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $buku->judul }}</h5>
                    <p class="card-text"><small class="text-muted">Penulis: {{ $buku->pengarang }}</small></p>
                    <p class="card-text"><small class="text-muted">Tersedia: {{ $buku->tersedia }}</small></p>
                </div>
            </div>
        </div>
    @endforeach
</div>
