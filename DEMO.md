# Skenario Demo Aplikasi Perpustakaan (UAS)

Dokumen ini adalah panduan langkah-demi-langkah demonstrasi aplikasi **Perpustakaan Digital Kampus** untuk evaluasi Ujian Akhir Semester (UAS). Skenario ini disusun sesuai indikator penilaian pada Pertemuan 11.

---

## 1. Persiapan Sebelum Demo (Pre-requisites)

Sebelum memulai sesi demo di depan dosen penguji, pastikan poin-poin berikut telah terpenuhi:

1. **Database Sudah Bersih & Ter-seed**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Hasil: 5 kategori, 20 buku (berjudul bahasa Indonesia), 15 anggota (NIM & email @pens.ac.id), 10 riwayat peminjaman, dan 3 akun petugas/admin.*

2. **Dua Instance Server Berjalan Bersamaan (Mencegah Deadlock HTTP Client)**
   - **Terminal 1 (Web Application):**
     ```bash
     php artisan serve --port=8000
     ```
   - **Terminal 2 (Internal API Endpoint):**
     ```bash
     php artisan serve --port=8011
     ```

3. **Kredensial Akun Login**
   | Role | Nama | Email | Password |
   |---|---|---|---|
   | **Admin** | Bambang Sutrisno | `admin@pens.ac.id` | `password` |
   | **Petugas** | Siti Rahmawati | `petugas1@pens.ac.id` | `password` |
   | **Petugas** | Ahmad Fauzi | `petugas2@pens.ac.id` | `password` |

4. **Aplikasi Pendukung Siap**
   - Browser (Google Chrome / Edge) terbuka di `http://127.0.0.1:8000`
   - Postman terbuka dengan request collection ke `http://127.0.0.1:8000/api`

---

## 2. Urutan 11 Langkah Demo Aplikasi

### Langkah 1: Login Petugas / Admin
1. Buka URL: `http://127.0.0.1:8000/login`
2. Masukkan email: `admin@pens.ac.id` dan password: `password`.
3. Klik tombol **Login**.
4. **Verifikasi:**
   - Berhasil redirect otomatis ke halaman Dashboard (`/` atau `/dashboard`).
   - Tampil flash message: *"Login berhasil, selamat datang Bambang Sutrisno."*
   - Di pojok kanan atas navbar tampil nama dan role: **Bambang Sutrisno (Admin)** beserta tombol **Logout**.

---

### Langkah 2: Tampilkan Dashboard Statistik
1. Amati halaman utama Dashboard (`http://127.0.0.1:8000/`).
2. Tunjukkan **3 Kartu Statistik**:
   - **Total Buku:** Menampilkan angka `20` (sesuai jumlah buku di database).
   - **Total Anggota:** Menampilkan angka `15` (sesuai jumlah anggota di database).
   - **Peminjaman Aktif:** Menampilkan jumlah peminjaman berstatus `dipinjam`.
3. **Poin Penjelasan:**
   - Jelaskan bahwa angka pada ketiga kartu ini tidak di-query langsung oleh Controller Web menggunakan Eloquent, melainkan diambil oleh `DashboardController` via **Laravel HTTP Client (`Http::get()`)** mengonsumsi endpoint internal `GET /api/stats` pada server port `8011`.
   - Kode dilengkapi penanganan `ConnectionException` sehingga jika server API mati, halaman tetap berjalan aman (fallback ke nilai `0`) tanpa error 500.

---

### Langkah 3: Tambah Kategori Baru (Fitur Khusus Admin)
1. Klik menu **Kategori** pada navbar (`http://127.0.0.1:8000/categories`).
   *(Catatan: Menu ini dilindungi middleware `CheckAdminRole` dan hanya muncul untuk akun Admin).*
2. Klik tombol **Tambah Kategori**.
3. Isi form:
   - **Nama Kategori:** `Ensiklopedia`
   - **Deskripsi:** `Buku referensi lengkap memuat ikhtisar ilmu pengetahuan.`
4. Klik tombol **Simpan**.
5. **Verifikasi:**
   - Redirect ke daftar kategori dengan pesan sukses.
   - Kategori `Ensiklopedia` muncul dalam tabel.

---

### Langkah 4: Tambah Buku dengan Relasi Kategori
1. Klik menu **Buku** pada navbar (`http://127.0.0.1:8000/books`).
2. Klik tombol **Tambah Buku Baru**.
3. Isi form buku:
   - **Judul:** `Ensiklopedia Sains dan Teknologi Dunia`
   - **Kategori:** Pilih `Ensiklopedia` dari dropdown (kategori yang baru ditambahkan).
   - **Penulis:** `Prof. Hendra Wijaya`
   - **Penerbit:** `Balai Pustaka`
   - **Tahun Terbit:** `2024`
   - **ISBN:** `9786020301234`
   - **Stok:** `5`
4. Klik tombol **Simpan**.
5. **Verifikasi:**
   - Buku baru berhasil tersimpan dan tampil pada halaman index `/books`.
   - Kolom Kategori menampilkan teks nama kategori `Ensiklopedia` (bukan ID mentah), membuktikan eager loading relasi `belongsTo` berjalan dengan baik.

---

### Langkah 5: Tambah Anggota Baru & Uji Validasi
1. Klik menu **Anggota** pada navbar (`http://127.0.0.1:8000/members`).
2. Klik tombol **Tambah Anggota Baru**.
3. Coba lakukan uji validasi (misal mengosongkan nama atau mengisi format email salah):
   - Klik Simpan -> Tampil error validasi merah di bawah input yang belum valid.
4. Isi data anggota valid:
   - **Nama:** `Raja Mahesa`
   - **NIM:** `3123500001`
   - **Email:** `raja.mahesa@pens.ac.id`
   - **Nomor Telepon:** `081234567890`
   - **Alamat:** `Jl. Raya ITS, Sukolilo, Surabaya`
   - **Status:** `Aktif`
5. Klik **Simpan**.
6. **Verifikasi:** Anggota baru muncul di daftar anggota dengan status badge aktif.

---

### Langkah 6: Buat Transaksi Peminjaman Baru
1. Klik menu **Peminjaman** (`http://127.0.0.1:8000/loans`).
2. Klik tombol **Tambah Peminjaman Baru** (`/loans/create`).
3. Pilih:
   - **Anggota:** Pilih `Raja Mahesa` (anggota yang baru dibuat).
   - **Buku yang Dipinjam:** Centang buku `Ensiklopedia Sains dan Teknologi Dunia`.
   - **Tanggal Pinjam:** Hari ini.
   - **Tanggal Kembali:** 7 hari ke depan.
4. Klik **Simpan Transaksi**.
5. **Poin Penjelasan:**
   - Field `user_id` petugas otomatis diisi dari `auth()->id()` sesi login, bukan dipilih manual lewat dropdown.
   - Menggunakan relasi `belongsTo` untuk Member & User, serta relasi `belongsToMany` / `hasMany` (via pivot `loan_items`) untuk buku yang dipinjam.

---

### Langkah 7: Tampilkan Daftar Peminjaman Aktif & Eager Loading
1. Pada halaman daftar peminjaman (`/loans`):
2. Tunjukkan baris transaksi yang baru saja dibuat.
3. **Poin Evaluasi:**
   - Status peminjaman menampilkan badge berwarna oranye/kuning (`Dipinjam`).
   - Tampil nama anggota (`Raja Mahesa`), nama petugas (`Bambang Sutrisno`), serta judul buku yang dipinjam.
   - Jelaskan bahwa query di Controller menggunakan **eager loading** `Loan::with(['member', 'user', 'books'])->paginate(10)` untuk mencegah **N+1 Query Problem**.

---

### Langkah 8: Proses Pengembalian Buku
1. Temukan baris transaksi milik `Raja Mahesa` yang berstatus `Dipinjam`.
2. Klik tombol **Kembalikan** (Tugas P-7).
3. Konfirmasi modal dialog browser.
4. **Verifikasi:**
   - Halaman me-refresh dengan flash message sukses: *"Buku berhasil dikembalikan."*
   - Status transaksi otomatis berubah menjadi `Dikembalikan` dengan badge hijau.
   - Field `tanggal_dikembalikan` tercatat otomatis sesuai tanggal hari ini.
   *(Alternatif jika mendemokan via API: Kirim `PUT /api/loans/{id}/kembalikan` dari Postman).*

---

### Langkah 9: Tampilkan Halaman Laporan Peminjaman (Internal API Client)
1. Klik menu **Laporan** pada navbar (`http://127.0.0.1:8000/loans/report`).
2. **Poin Evaluasi:**
   - Halaman menampilkan tabel rekapitulasi transaksi peminjaman.
   - Transaksi peminjaman yang baru saja dibuat dan dikembalikan tampil pada tabel laporan.
   - Terdapat navigasi halaman (Pagination) berbasis metadata API (`meta.current_page` dan `meta.last_page`).
3. **Penjelasan Teknis:**
   - Data laporan ini diambil oleh `LoanController@report` dengan memanggil `GET http://127.0.0.1:8011/api/loans?page=...` menggunakan Laravel `Http` client.
   - Controller web mem-parsing format respons JSON dan merendernya ke view Blade.

---

### Langkah 10: Demo REST API di Postman
Buka Postman dan demonstrasikan endpoint REST API berikut:

1. **`GET /api/books`**
   - URL: `http://127.0.0.1:8000/api/books` (atau port `8011`)
   - Tunjukkan respons JSON terstruktur menggunakan `BookResource`:
     ```json
     {
       "data": [
         {
           "id": 1,
           "judul": "...",
           "penulis": "...",
           "penerbit": "...",
           "tahun_terbit": 2024,
           "stok": 5,
           "category": {
             "id": 1,
             "nama_kategori": "Novel"
           }
         }
       ],
       "links": { ... },
       "meta": { ... }
     }
     ```
   - Tunjukkan bahwa response dilengkapi pagination metadata otomatis dari Laravel API Resource.

2. **`GET /api/stats`**
   - URL: `http://127.0.0.1:8000/api/stats`
   - Tunjukkan respons JSON statistik real-time:
     ```json
     {
       "total_buku": 21,
       "total_anggota": 16,
       "peminjaman_aktif": 1
     }
     ```
   - Tunjukkan angka bertambah sesuai buku dan anggota yang baru saja ditambahkan saat demo.

3. *(Opsional Tambahan)* **`GET /api/members`** dan **`GET /api/loans`** untuk membuktikan resource lengkap telah diimplementasikan.

---

### Langkah 11: Ringkasan Tanya Jawab Konseptual
Berikut adalah poin kunci untuk menjawab 13 pertanyaan konseptual penguji:

1. **Tanpa Framework vs MVC:** Tanpa framework, kode PHP bercampur antara query SQL, logika bisnis, dan HTML (spaghetti code), sulit dirawat seiring aplikasi membesar. Arsitektur MVC memisahkan tanggung jawab data (Model), tampilan (View), dan alur logika (Controller).
2. **Alur Request-Response Form Buku:** Browser mengirim HTTP POST -> Masuk Web Server -> Laravel Routing (`routes/web.php`) -> Melewati Middleware `auth` -> `BookController@store` -> Validasi input (`$request->validate()`) -> `Book::create()` ke DB -> Redirect ke `books.index` dengan session flash message -> View Blade di-render menjadi HTML -> Dikirim kembali ke browser.
3. **Validasi Sisi Server vs HTML `required`:** Atribut HTML `required` berada di sisi klien dan sangat mudah dimanipulasi atau di-bypass menggunakan inspect element atau tools HTTP seperti Postman/cURL. Validasi server adalah gerbang keamanan mutlak untuk integritas data.
4. **Fungsi Migration:** Migration berperan sebagai *version control* untuk skema basis data tim. Setiap perubahan kolom/tabel tercatat berurutan, dapat di-rollback, dan konsisten dijalankan di semua komputer tim tanpa impor file `.sql` manual.
5. **N+1 Query Problem & Eager Loading:** Terjadi jika kita me-looping 10 data `Loan` lalu di setiap baris memanggil relasi `$loan->member->nama`. Laravel akan menjalankan 1 query awal + 10 query tambahan per anggota (total 11 query). Eager loading (`with(['member', 'books'])`) menyelesaikan ini dengan hanya mengeksekusi 2-3 query menggunakan klausa `WHERE IN`, meringankan beban database secara drastis.
6. **Relasi Model Eloquent vs Foreign Key DB:** Foreign key di level database menjaga integritas referensial (mencegah data yatim/orphan record via `ON DELETE CASCADE/RESTRICT`). Relasi di Model Eloquent (`belongsTo`, `hasMany`) adalah abstraksi PHP yang mempermudah developer mengakses objek terkait (`$book->category->nama_kategori`) dan eager loading.
7. **Autentikasi vs Otorisasi:** Autentikasi membuktikan **siapa anda** (identitas pengguna lewat login email & password di `AuthController`). Otorisasi membuktikan **apa hak akses anda** (apakah boleh mengelola kategori lewat middleware `CheckAdminRole`).
8. **REST API JSON vs HTML:** JSON adalah format data netral, ringan, dan universal yang dapat dikonsumsi oleh web app, aplikasi mobile (Android/iOS), frontend modern (React/Vue/Flutter), maupun microservice lainnya, bukan hanya browser berbasis web.
9. **Fungsi API Resource:** API Resource memisahkan representasi JSON dari struktur tabel database. Melindungi field sensitif, membakukan format output snake_case/camelCase, memformat tanggal, dan memastikan perubahan kolom database tidak langsung merusak integrasi API klien.
10. **Kebutuhan 2 Server Development:** `php artisan serve` menggunakan PHP built-in web server yang bersifat *single-threaded* (hanya memproses satu request dalam satu waktu). Saat Controller memanggil API di port yang sama, terjadi *deadlock* karena request pertama menunggu balasan dari request kedua yang belum bisa diterima server. Di server produksi (Nginx + PHP-FPM), banyak worker memproses request secara paralel sehingga deadlock ini tidak terjadi.
11. **Seeder vs Factory:** Seeder mendefinisikan *apa data yang ingin diisi* (misal 5 kategori tetap atau akun admin). Factory mendefinisikan *cetak biru / blueprint pembentukan data acak* menggunakan Faker. Keduanya dipisah agar factory dapat digunakan berulang-ulang dengan jumlah dinamis (`Book::factory()->count(20)`).
12. **Alur Menambah Tabel Baru (misal `publishers`):**
    `php artisan make:model Publisher -mcr` -> Buat skema migration -> Jalankan migration -> Definisikan `$fillable` dan relasi di Model -> Buat Controller & validasi -> Daftarkan Route (`routes/web.php`) -> Buat View Blade di `resources/views/publishers/`.
13. **Manfaat 2 Branch & Commit Bertahap:** Branch `dev` dipakai untuk pengembangan aktif dan eksperimen fitur tanpa merusak versi stabil di `main`. Commit checkpoint berkala (`[P-1]`, `[P-2]`, dst) mendokumentasikan jejak evolusi kode secara terstruktur, memudahkan isolasi bug, dan membuktikan progres kerja yang konsisten.
