<?php

namespace App\Providers;

use App\Models\Anggota;
use App\Models\User;
use App\Policies\AnggotaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

    
    public function boot(): void
    {
        // Pemeriksaan kepemilikan data peminjaman (lihat AnggotaPolicy).
        Gate::policy(Anggota::class, AnggotaPolicy::class);

        // Modul 6: satu aturan pusat untuk pengelolaan buku (tambah, ubah, hapus).
        // Hanya role admin dan petugas; dipakai oleh middleware can:kelola-buku dan @can('kelola-buku').
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}
