@extends('layouts.app')

@section('judul', 'Laporan Perpustakaan')

@section('konten')
    <h2>Laporan Perpustakaan</h2>

    <h3>Peminjaman menurut status</h3>
    <div class="statistik">
        @foreach (\App\Models\Peminjaman::DAFTAR_STATUS as $s)
            <div class="statistik__kartu">
                <span class="statistik__angka">{{ $perStatus[$s] ?? 0 }}</span>
                <span class="statistik__label">{{ ucfirst($s) }}</span>
            </div>
        @endforeach
    </div>

    <h3>Jumlah buku per kategori</h3>
    <div class="tabel-gulir">
        <table class="tabel">
            <thead>
                <tr><th>No</th><th>Kategori</th><th>Jumlah buku</th></tr>
            </thead>
            <tbody>
                @forelse ($bukuPerKategori as $i => $kategori)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $kategori->nama }}</td>
                        <td>{{ $kategori->buku_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="tabel__info">Belum ada data.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><th colspan="2">Total</th><th>{{ $bukuPerKategori->sum('buku_count') }}</th></tr>
            </tfoot>
        </table>
    </div>

    <h3>Jumlah peminjaman per buku</h3>
    <div class="tabel-gulir">
        <table class="tabel">
            <thead>
                <tr><th>No</th><th>Judul</th><th>Kategori</th><th>Total peminjaman</th></tr>
            </thead>
            <tbody>
                @forelse ($peminjamanPerBuku as $i => $buku)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->kategori->nama }}</td>
                        <td>{{ $buku->peminjaman_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="tabel__info">Belum ada data.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr><th colspan="3">Total</th><th>{{ $peminjamanPerBuku->sum('peminjaman_count') }}</th></tr>
            </tfoot>
        </table>
    </div>
@endsection
