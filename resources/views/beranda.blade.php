@extends('layouts.app')

@section('judul', 'Beranda')

@section('konten')
    <section class="hero">
        <p class="hero__label">Selamat datang</p>
        <h2>Cari dan telusuri koleksi buku perpustakaan kami.</h2>
        <p class="hero__deskripsi">
            Halaman ini menampilkan katalog buku yang tersedia, lengkap dengan penulis,
            tahun terbit, dan kategori rak masing-masing.
        </p>
        <a href="{{ route('buku.index') }}" class="tombol">Lihat daftar buku</a>
    </section>
@endsection