# A-SMS Sistem Manajemen Sekolah (Simple)

Aplikasi manajemen sekolah berbasis web (Laravel + Blade) dengan antarmuka bahasa Indonesia. Dirancang ringkas: **RBAC** (peran & izin), **master data** (tingkat, kelas, mata pelajaran, **tahun ajaran**), **penjadwalan** jadwal mengajar, **akademik** (jadwal guru, nilai, absensi, rekap), dan dukungan **mode gelap**.

---

## Cuplikan layar

![Master Data — Tahun Ajaran](screenshots/tahun-ajaran.png)

*Halaman **Tahun Ajaran**: data master periode tahun ajaran dipakai untuk penjadwalan dan pemilihan tahun ajaran di filter jadwal (default ke tahun ajaran terbaru).*

---

## Fitur ringkas

- Autentikasi (login, register, reset password, verifikasi email)
- **Manajemen akses**: pengguna, peran, izin akses
- **Pengguna sekolah**: pegawai, guru, siswa (terhubung ke akun login)
- **Master data**: tingkat, kelas, mata pelajaran, tahun ajaran
- **Penjadwalan** jadwal per kelas / mapel / guru
- **Akademik**: jadwal mengajar (guru & tampilan admin dengan filter guru/tahun ajaran), nilai, absensi, rekap wali kelas, rekap absensi
- UI responsif, footer versi aplikasi, dukungan dark mode

---

## Persyaratan

- PHP 8.2+
- Composer
- Database (MySQL / MariaDB / SQLite, sesuai `DATABASE_*` di `.env`)

---

## Instalasi

```bash
# Clone repositori proyek ini, lalu masuk ke folder aplikasi.
cd a-sms
composer install
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database di `.env`, lalu:

```bash
php artisan migrate --force
php artisan db:seed --class=RolePermissionSeeder --force
```

### Data uji coba (opsional)

Untuk mengisi **tingkat**, **mata pelajaran**, **guru** (beserta akun login), **kelas** dengan wali kelas, **siswa** per kelas, dan **jadwal** tahun ajaran `2025/2026`, jalankan:

```bash
php artisan db:seed --class=SchoolSampleSeeder --force
```

Seeder ini juga memanggil `RolePermissionSeeder` di dalamnya, jadi izin dan akun demo Super Admin / Admin Sistem tetap tersedia. Data dibuat dengan `updateOrCreate` (aman dijalankan ulang; baris yang sama akan disesuaikan, bukan duplikat acak).

**Akun contoh dari data uji (password semua: `password`):**

| Peran | Contoh email |
|-------|----------------|
| Guru | `guru.budi@example.com` … `guru.yusuf@example.com` (6 guru) |
| Siswa | `siswa.1A.01@example.com` … per kombinasi kelas & nomor (lihat seeder) |

Login sebagai guru berguna untuk menguji **Jadwal Mengajar**, nilai, dan absensi; sebagai Super Admin untuk melihat **semua jadwal** dengan filter guru/tahun ajaran.

Jalankan server pengembangan:

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` dan login dengan akun demo di bawah.

---

## Akun demo (setelah `RolePermissionSeeder` atau `SchoolSampleSeeder`)

| Peran        | Email                 | Password  |
|-------------|------------------------|-----------|
| Super Admin | superadmin@example.com | password  |
| Admin Sistem| admin@example.com    | password  |

Akun **guru** dan **siswa** hanya muncul jika Anda menjalankan **`SchoolSampleSeeder`** (lihat bagian *Data uji coba* di atas).

---

## Dokumentasi tambahan

- [RBAC_API_GUIDE.md](RBAC_API_GUIDE.md) — RBAC & API Sanctum
- [DEPLOYMENT_SHARED_HOSTING.md](DEPLOYMENT_SHARED_HOSTING.md) — panduan deploy shared hosting
- [QUICK_REFERENCE.md](QUICK_REFERENCE.md) — referensi cepat
- [UI_GUIDE.md](UI_GUIDE.md) — panduan antarmuka
- [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) — ringkasan teknis

---

## Lisensi

Perangkat lunak open source di bawah lisensi MIT.
