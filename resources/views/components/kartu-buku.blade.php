@props([
    'nomor' => null,
    'judul',
    'penulis',
    'tahunTerbit',
    'url',
])

<article class="kartu-buku">
    @if ($nomor)
        <span class="kartu-buku__nomor">No. {{ str_pad($nomor, 3, '0', STR_PAD_LEFT) }}</span>
    @endif

    <h3 class="kartu-buku__judul">{{ $judul }}</h3>
    <p class="kartu-buku__penulis">{{ $penulis }}</p>

    <dl class="kartu-buku__meta">
        <div>
            <dt>Terbit</dt>
            <dd>{{ $tahunTerbit }}</dd>
        </div>
        @isset($kategori)
            <div>
                <dt>Rak</dt>
                <dd>{{ $kategori }}</dd>
            </div>
        @endisset
    </dl>

    <a href="{{ $url }}" class="tombol">Lihat detail</a>
</article>
