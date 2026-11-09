# Modul 5: Autentikasi & RBAC (lanjutan proyek Modul 4)

## 1. Cara menjalankan (urutan penting)

```bash
# 1) Pasang package (Breeze hanya sebagai dependensi pengembangan, Spatie wajib)
composer require laravel/breeze --dev
composer require spatie/laravel-permission

# 2) Publikasikan migration + config/permission.php milik Spatie
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 3) Bersihkan cache konfigurasi, lalu migrasi + isi data
php artisan config:clear
php artisan migrate:fresh --seed      # data lama dihapus lalu diisi ulang seeder
#   (atau tanpa menghapus data lama:)
#   php artisan migrate
#   php artisan db:seed --class=RoleSeeder
#   php artisan db:seed --class=UserSeeder

# 4) Jalankan
npm install
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

> **Jangan menjalankan `php artisan breeze:install`.** Kode Breeze (stack Blade) sudah dipasang dan
> disesuaikan dengan layout proyek. Perintah itu akan menimpa `routes/web.php`, `layouts/app.blade.php`,
> `app.css`, dan `app.js` milik proyekmu.

## 2. Akun contoh (dibuat seeder, kata sandi: `password`)

| Role    | Email                |
|---------|----------------------|
| Admin   | admin@gmail.com      |
| Petugas | petugas@gmail.com    |
| Siswa   | siswa@gmail.com      |

Registrasi biasa (`/register`) selalu menghasilkan role **siswa**. Role hanya bisa diubah Admin
lewat menu **Pengguna**.

## 3. Pemetaan permission (sesuai tabel soal no. 4)

| No | Permission                           | Admin | Petugas | Siswa | Dipakai pada |
|----|--------------------------------------|:-----:|:-------:|:-----:|--------------|
| 1  | akses dashboard admin                | ✓ | — | — | `/admin/dashboard` |
| 2  | akses dashboard petugas              | — | ✓ | — | `/petugas/dashboard` |
| 3  | lihat daftar buku                    | ✓ | ✓ | ✓ | `/buku`, `/buku/{id}`, `GET /api/buku` |
| 4  | kelola data buku                     | ✓ | ✓ | — | `POST/PUT/DELETE /api/buku`, form di `/buku` |
| 5  | kelola data pengguna                 | ✓ | — | — | `/admin/pengguna` (CRUD) |
| 6  | kelola role pengguna                 | ✓ | — | — | `PATCH /admin/pengguna/{id}/role` |
| 7  | kelola peminjaman                    | ✓ | ✓ | — | `/kelola/peminjaman` |
| 8  | kelola pengembalian                  | ✓ | ✓ | — | `/kelola/pengembalian` |
| 9  | mengajukan peminjaman buku           | ✓ | ✓ | ✓ | `POST /peminjaman` (tombol di detail buku) |
| 10 | memproses persetujuan peminjaman     | ✓ | ✓ | — | setujui / tolak |
| 11 | mencatat pengembalian buku           | ✓ | ✓ | — | tombol "Catat pengembalian" |
| 12 | melihat status peminjaman sendiri    | ✓ | ✓ | ✓ | `/peminjaman-saya` |
| 13 | melihat riwayat peminjaman sendiri   | ✓ | ✓ | ✓ | `/riwayat-peminjaman`, `/anggota/{id}/riwayat` |
| 14 | lihat laporan perpustakaan           | ✓ | ✓ | — | `/laporan`, `/api/rekap/*` |
| 15 | lihat profil sendiri                 | ✓ | ✓ | ✓ | `GET /profile` |
| 16 | ubah profil sendiri                  | ✓ | ✓ | ✓ | `PATCH /profile`, `PUT /password` |

Data pribadi (profil, riwayat) tidak cukup dicek lewat permission: profil selalu memakai
`$request->user()`, dan riwayat diperiksa lagi di `AnggotaPolicy` (`anggota.user_id == user.id`).

## 4. Perubahan pada struktur proyek

* `anggotas.user_id` (migration baru) menghubungkan akun login dengan profil anggota.
* `peminjamans.status` (migration baru): `diajukan` → `dipinjam` → `dikembalikan` (atau `ditolak`).
  Dibutuhkan agar alur "mengajukan" dan "memproses persetujuan" pada soal bisa berjalan.
* `routes/api.php` dibungkus middleware `web` + `auth` supaya API mengenali login lewat session;
  `public/js/buku-client.js` kini mengirim token CSRF dan menangani 401/403/419.
* Fitur Breeze yang tidak diminta soal (reset password, verifikasi email, konfirmasi password) tidak dipasang.

## 5. Pengujian

Otomatis: `php artisan test --filter=AksesPerpustakaanTest`

Manual: isi kolom **Hasil Aktual** berdasarkan yang kamu lihat sendiri (sertakan screenshot di laporan).

| No | Role | Skenario | Hasil yang diharapkan | Cara uji | Hasil Aktual |
|----|------|----------|-----------------------|----------|--------------|
| 1  | Belum login | Buka dashboard admin | Dialihkan ke halaman login | Buka `/admin/dashboard` tanpa login | |
| 2  | Admin | Buka dashboard admin | Dashboard admin dapat diakses | Login admin → `/admin/dashboard` | |
| 3  | Admin | Tambah, ubah, hapus buku | Operasi berhasil | Menu Daftar Buku → form & tombol Ubah/Hapus | |
| 4  | Admin | Kelola akun dan role | Operasi berhasil | Menu Pengguna → tambah/ubah/hapus, ganti role | |
| 5  | Admin | Akses peminjaman, pengembalian, laporan | Data dapat diakses | Menu Kelola Peminjaman, Pengembalian, Laporan | |
| 6  | Petugas | Buka dashboard petugas | Dashboard petugas dapat diakses | Login petugas → `/petugas/dashboard` | |
| 7  | Petugas | Kelola data buku | Operasi berhasil | Menu Daftar Buku | |
| 8  | Petugas | Proses peminjaman dan pengembalian | Operasi berhasil | Setujui pengajuan, lalu catat pengembalian | |
| 9  | Petugas | Buka pengelolaan akun/role | Akses ditolak | Buka `/admin/pengguna` langsung di URL (403) | |
| 10 | Siswa | Lihat daftar dan detail buku | Data buku dapat dilihat | `/buku` dan klik judul | |
| 11 | Siswa | Lihat dan ubah profil sendiri | Profil dapat diakses dan diperbarui | Menu Profil | |
| 12 | Siswa | Lihat riwayat peminjaman sendiri | Riwayat milik sendiri dapat dilihat | Menu Riwayat Saya | |
| 13 | Siswa | Buka dashboard admin / pengelolaan buku | Akses ditolak | `/admin/dashboard` (403); form kelola buku tidak tampil | |
| 14 | Siswa | Lihat riwayat siswa lain | Akses ditolak | Buka `/anggota/{id anggota lain}/riwayat` (403) | |
| 15 | Semua | Logout lalu buka halaman terlindungi | Harus login kembali | Klik Keluar → buka `/dashboard` | |
