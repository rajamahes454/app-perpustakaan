# 📚 Aplikasi Perpustakaan Digital Kampus (Laravel 12)

Aplikasi manajemen perpustakaan berbasis web dan REST API yang dibangun menggunakan framework **Laravel 12**, arsitektur MVC, Eloquent ORM, otorisasi berbasis peran (Role-Based Access Control), dan konsumsi API internal via Laravel HTTP Client.

Project ini dikembangkan sebagai tugas praktikum mata kuliah Pemrograman Web Semester 3 secara bertahap dari **Pertemuan 1 hingga Pertemuan 10**, siap dievaluasi pada Ujian Akhir Semester (Pertemuan 11).

---

## 🌟 Fitur Utama

### 1. Manajemen Web (Blade & MVC)
- **Dashboard Statistik:** Ringkasan jumlah buku, total anggota, dan peminjaman aktif yang dikonsumsi secara real-time dari internal API (`GET /api/stats`).
- **Autentikasi & Middleware:** Sistem login/logout berbasis session, proteksi route dengan middleware `auth`, dan pembagian hak akses menggunakan middleware `CheckAdminRole`.
- **Profil Petugas & Ganti Password:** Halaman informasi profil akun petugas yang sedang login beserta fitur ubah kata sandi dengan verifikasi password lama.
- **CRUD Buku & Kategori:** Pengelolaan data buku lengkap dengan relasi kategori (`belongsTo`), validasi input Form Request, ISBN unik, dan stok buku.
- **CRUD Anggota & Pencarian:** Pengelolaan data anggota perpustakaan dengan NIM & email unik civitas kampus `@pens.ac.id`, dilengkapi fitur pencarian nama anggota.
- **Transaksi Peminjaman:** Peminjaman multi-buku dalam satu transaksi, pencatatan otomatis petugas login (`user_id`), status badge warna (`dipinjam`, `dikembalikan`, `terlambat`), dan fitur proses pengembalian buku sekali klik.
- **Laporan Peminjaman:** Halaman rekapitulasi data peminjaman yang mengonsumsi endpoint internal `GET /api/loans` dilengkapi pagination metadata.
- **Custom Pagination:** Tampilan pagination bersih tanpa dependensi Tailwind CSS, terintegrasi global via `Paginator::defaultView()`.

### 2. REST API Terstruktur
- 11 endpoint REST API untuk entitas `books`, `members`, `loans`, dan `stats`.
- Output JSON distandarisasi menggunakan **Laravel API Resource** (`BookResource`, `MemberResource`, `LoanResource`).
- Dilengkapi pagination otomatis dan HTTP status code semantik (200, 201, 404, 422).
- Dokumentasi lengkap parameter request & contoh response JSON tersedia di [API.md](./API.md).

---

## 💻 Kebutuhan Sistem

- **PHP:** >= 8.2 (ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`)
- **Composer:** >= 2.0
- **Database:** MySQL >= 8.0 / MariaDB >= 10.4

---

## 🚀 Panduan Instalasi & Menjalankan Project

### 1. Clone Repository
```bash
git clone https://github.com/rajamahes454/app-perpustakaan.git
cd app-perpustakaan
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Salin Konfigurasi Environment
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Pastikan konfigurasi database dan `INTERNAL_API_URL` sudah sesuai:
```ini
APP_NAME=AppPerpustakaan
APP_URL=http://127.0.0.1:8000
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app_perpustakaan
DB_USERNAME=root
DB_PASSWORD=

# Base URL instance kedua untuk consume API internal di Dashboard & Laporan
INTERNAL_API_URL=http://127.0.0.1:8011
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Migrasi & Seeding Database
Jalankan migrasi seluruh tabel beserta data awal bergaya Indonesia (kategori kurasi, 20 buku realistis, 15 anggota, 10 riwayat pinjam, dan akun pengguna):
```bash
php artisan migrate:fresh --seed
```

---

## ⚠️ Menjalankan Dua Server Sekaligus

> **PENTING:** Built-in web server `php artisan serve` bersifat *single-threaded* (hanya memproses satu request dalam satu waktu). Karena halaman **Dashboard** (`/`) dan **Laporan** (`/loans/report`) mengonsumsi endpoint API dari aplikasi ini sendiri via HTTP Client, aplikasi **membutuhkan 2 instance server** berjalan bersamaan pada terminal terpisah agar tidak terjadi **deadlock**:

1. **Terminal 1 — Server Web Utama (Port 8000):**
   ```bash
   php artisan serve --port=8000
   ```
   *Buka di browser: `http://127.0.0.1:8000`*

2. **Terminal 2 — Server Internal API (Port 8011):**
   ```bash
   php artisan serve --port=8011
   ```
   *Server ini menerima panggilan internal `Http::get()` dari Controller.*

*(Catatan: Di lingkungan produksi sungguhan dengan web server multi-worker seperti Nginx/PHP-FPM, kebutuhan dua server ini tidak diperlukan).*

---

## 🔑 Kredensial Login Bawaan

| Role | Nama | Email | Password | Hak Akses |
|---|---|---|---|---|
| **Admin** | Bambang Sutrisno | `admin@pens.ac.id` | `password` | Seluruh fitur + Kelola Kategori |
| **Petugas** | Siti Rahmawati | `petugas1@pens.ac.id` | `password` | Dashboard, Buku, Anggota, Peminjaman, Laporan |
| **Petugas** | Ahmad Fauzi | `petugas2@pens.ac.id` | `password` | Dashboard, Buku, Anggota, Peminjaman, Laporan |

---

## 📖 Dokumentasi Terkait

- 📄 [**API.md**](./API.md): Dokumentasi lengkap skema 11 endpoint REST API, parameter query, body request, HTTP response codes, dan contoh output JSON.
- 🎬 [**DEMO.md**](./DEMO.md): Panduan skenario 11 langkah demo aplikasi untuk ujian UAS beserta rangkuman tanya jawab konseptual penguji.

---

## 📌 Riwayat Perkuliahan & Checkpoint Git

Project ini dikembangkan dengan commit checkpoint yang rapi dan terisolasi:

- `[P-1]` Inisialisasi project Laravel 12 & struktur direktori MVC
- `[P-2]` Routing, Resource Route, dan alur request lifecycle
- `[P-3]` Controller, Form Request, dan validasi server-side
- `[P-4]` Blade templating, layout inheritance, dan partial views
- `[P-5]` Database migration, model Eloquent, dan CRUD data nyata
- `[Tugas P-5]` Fitur pencarian anggota berdasarkan nama
- `[P-7]` Eloquent relationships (`belongsTo`, `hasMany`), eager loading, & CRUD Peminjaman
- `[Tugas P-7]` Fitur pengembalian buku dan status badge warna-warni
- `[P-8]` Autentikasi session, logout CSRF, dan middleware otorisasi `CheckAdminRole`
- `[Tugas P-8]` Halaman profil petugas & fitur ganti password terenkripsi
- `[P-9]` Implementasi REST API books, members, loans, stats dengan API Resource
- `[Tugas P-9]` Dokumentasi lengkap spesifikasi REST API di `API.md`
- `[P-10]` Konsumsi internal API di Dashboard & Laporan, Seeder & Factory Indonesia, custom pagination
- `[Tugas P-10]` Merge `dev` ke `main`, penyusunan `DEMO.md`, dan finalisasi `README.md`
- `[P-11]` Persiapan demonstrasi live UAS dan evaluasi akhir

---

**Pengembang:** Raja Mahesa  
**Repository:** [github.com/rajamahes454/app-perpustakaan](https://github.com/rajamahes454/app-perpustakaan)  
**Lisensi:** MIT
