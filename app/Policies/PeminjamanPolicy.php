<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Admin dan petugas boleh melihat semua peminjaman;
     * siswa hanya boleh melihat peminjaman miliknya sendiri.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        return $user->hasRole('siswa')
            && $peminjaman->anggota->user_id === $user->id;
    }
}
