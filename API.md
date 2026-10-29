# Dokumentasi REST API - Sistem Perpustakaan Digital Kampus

Base URL: `http://127.0.0.1:8000/api`  
Header: `Accept: application/json`

---

## Ringkasan Endpoint

| No | Method | URL | Deskripsi | Status Code |
|---|---|---|---|---|
| 1 | `GET` | `/api/books` | Mengambil daftar buku (dengan filter & pagination) | `200 OK` |
| 2 | `GET` | `/api/books/{id}` | Mengambil detail 1 buku | `200 OK` / `404 Not Found` |
| 3 | `POST` | `/api/books` | Menambahkan buku baru | `201 Created` / `422 Unprocessable` |
| 4 | `PUT` | `/api/books/{id}` | Memperbarui data buku | `200 OK` / `404 Not Found` / `422 Unprocessable` |
| 5 | `DELETE` | `/api/books/{id}` | Menghapus buku | `200 OK` / `404 Not Found` |
| 6 | `GET` | `/api/members` | Mengambil daftar anggota (dengan pagination) | `200 OK` |
| 7 | `GET` | `/api/members/{id}` | Mengambil detail 1 anggota | `200 OK` / `404 Not Found` |
| 8 | `GET` | `/api/loans` | Mengambil daftar peminjaman | `200 OK` |
| 9 | `POST` | `/api/loans` | Membuat transaksi peminjaman baru | `201 Created` / `422 Unprocessable` |
| 10 | `PUT` | `/api/loans/{id}/kembalikan` | Memproses pengembalian buku | `200 OK` / `404 Not Found` |
| 11 | `GET` | `/api/stats` | Mengambil statistik ringkas perpustakaan | `200 OK` |

---

## Detail Endpoint

### 1. `GET /api/books`
- **Deskripsi:** Mendapatkan daftar buku dengan pagination.
- **Query Params:**
  - `judul` (opsional): filter judul buku (`LIKE`)
  - `category_id` (opsional): filter id kategori
  - `page` (opsional): nomor halaman
- **Status:** `200 OK`
- **Response:**
```json
{
  "data": [
    {
      "id": 1,
      "judul": "Clean Code",
      "penulis": "Robert C. Martin",
      "penerbit": "Prentice Hall",
      "tahun_terbit": "2008",
      "isbn": "9780132350884",
      "stok": 7,
      "sampul": null,
      "category": {
        "id": 1,
        "nama_kategori": "Teknologi"
      },
      "created_at": "2026-09-25T05:31:46.000000Z",
      "updated_at": "2026-09-25T05:31:46.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/books?page=1",
    "last": "http://127.0.0.1:8000/api/books?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://127.0.0.1:8000/api/books",
    "per_page": 10,
    "to": 2,
    "total": 2
  }
}
```

---

### 2. `GET /api/books/{id}`
- **Deskripsi:** Mendapatkan detail satu buku berdasarkan ID.
- **Status Sukses:** `200 OK`
- **Response (200 OK):**
```json
{
  "data": {
    "id": 1,
    "judul": "Clean Code",
    "penulis": "Robert C. Martin",
    "penerbit": "Prentice Hall",
    "tahun_terbit": "2008",
    "isbn": "9780132350884",
    "stok": 7,
    "sampul": null,
    "category": {
      "id": 1,
      "nama_kategori": "Teknologi"
    },
    "created_at": "2026-09-25T05:31:46.000000Z",
    "updated_at": "2026-09-25T05:31:46.000000Z"
  }
}
```
- **Response (404 Not Found):**
```json
{
  "message": "No query results for model [App\\Models\\Book] 999"
}
```

---

### 3. `POST /api/books`
- **Deskripsi:** Menambahkan buku baru.
- **Request Body (JSON):**
```json
{
  "judul": "Refactoring",
  "penulis": "Martin Fowler",
  "penerbit": "Addison-Wesley",
  "tahun_terbit": 2018,
  "isbn": "9780134757599",
  "stok": 5,
  "category_id": 1
}
```
- **Response (201 Created):**
```json
{
  "data": {
    "id": 3,
    "judul": "Refactoring",
    "penulis": "Martin Fowler",
    "penerbit": "Addison-Wesley",
    "tahun_terbit": 2018,
    "isbn": "9780134757599",
    "stok": 5,
    "sampul": null,
    "category": {
      "id": 1,
      "nama_kategori": "Teknologi"
    },
    "created_at": "2026-10-29T06:50:00.000000Z",
    "updated_at": "2026-10-29T06:50:00.000000Z"
  }
}
```
- **Response (422 Unprocessable Content):**
```json
{
  "message": "Judul buku wajib diisi. (and 5 more errors)",
  "errors": {
    "judul": ["Judul buku wajib diisi."],
    "penulis": ["Nama penulis wajib diisi."],
    "penerbit": ["Nama penerbit wajib diisi."],
    "tahun_terbit": ["Tahun terbit wajib diisi."],
    "stok": ["Stok wajib diisi."],
    "category_id": ["Kategori wajib dipilih."]
  }
}
```

---

### 4. `PUT /api/books/{id}`
- **Deskripsi:** Memperbarui data buku yang ada.
- **Request Body (JSON):**
```json
{
  "judul": "Clean Code: A Handbook of Agile Software Craftsmanship",
  "penulis": "Robert C. Martin",
  "penerbit": "Prentice Hall",
  "tahun_terbit": 2008,
  "isbn": "9780132350884",
  "stok": 8,
  "category_id": 1
}
```
- **Response (200 OK):**
```json
{
  "data": {
    "id": 1,
    "judul": "Clean Code: A Handbook of Agile Software Craftsmanship",
    "penulis": "Robert C. Martin",
    "penerbit": "Prentice Hall",
    "tahun_terbit": 2008,
    "isbn": "9780132350884",
    "stok": 8,
    "sampul": null,
    "category": {
      "id": 1,
      "nama_kategori": "Teknologi"
    },
    "created_at": "2026-09-25T05:31:46.000000Z",
    "updated_at": "2026-10-29T06:51:00.000000Z"
  }
}
```

---

### 5. `DELETE /api/books/{id}`
- **Deskripsi:** Menghapus buku berdasarkan ID.
- **Response (200 OK):**
```json
{
  "message": "Buku berhasil dihapus."
}
```

---

### 6. `GET /api/members`
- **Deskripsi:** Mengambil daftar seluruh anggota perpustakaan.
- **Status:** `200 OK`
- **Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nama": "Ahmad Fadli",
      "nim": "3125600101",
      "email": "ahmad.fadli@pens.ac.id",
      "nomor_telepon": "081234567890",
      "alamat": "Surabaya",
      "status": "aktif",
      "created_at": "2026-09-25T05:31:46.000000Z",
      "updated_at": "2026-09-25T05:31:46.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/members?page=1",
    "last": "http://127.0.0.1:8000/api/members?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://127.0.0.1:8000/api/members",
    "per_page": 10,
    "to": 3,
    "total": 3
  }
}
```

---

### 7. `GET /api/members/{id}`
- **Deskripsi:** Mengambil detail satu anggota perpustakaan.
- **Response (200 OK):**
```json
{
  "data": {
    "id": 1,
    "nama": "Ahmad Fadli",
    "nim": "3125600101",
    "email": "ahmad.fadli@pens.ac.id",
    "nomor_telepon": "081234567890",
    "alamat": "Surabaya",
    "status": "aktif",
    "created_at": "2026-09-25T05:31:46.000000Z",
    "updated_at": "2026-09-25T05:31:46.000000Z"
  }
}
```

---

### 8. `GET /api/loans`
- **Deskripsi:** Mengambil daftar transaksi peminjaman lengkap beserta data anggota, petugas, dan buku.
- **Status:** `200 OK`
- **Response:**
```json
{
  "data": [
    {
      "id": 1,
      "member": {
        "id": 1,
        "nama": "Ahmad Fadli"
      },
      "petugas": {
        "id": 1,
        "name": "Admin Perpustakaan"
      },
      "tanggal_pinjam": "2026-10-01",
      "tanggal_kembali": "2026-10-15",
      "tanggal_dikembalikan": null,
      "status": "dipinjam",
      "books": [
        {
          "id": 1,
          "judul": "Clean Code"
        }
      ]
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/loans?page=1",
    "last": "http://127.0.0.1:8000/api/loans?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://127.0.0.1:8000/api/loans",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

---

### 9. `POST /api/loans`
- **Deskripsi:** Membuat transaksi peminjaman baru (satu transaksi bisa mencakup banyak buku).
- **Request Body (JSON):**
```json
{
  "member_id": 1,
  "user_id": 1,
  "tanggal_pinjam": "2026-10-29",
  "tanggal_kembali": "2026-11-12",
  "book_ids": [1, 2]
}
```
- **Response (201 Created):**
```json
{
  "data": {
    "id": 2,
    "member": {
      "id": 1,
      "nama": "Ahmad Fadli"
    },
    "petugas": {
      "id": 1,
      "name": "Admin Perpustakaan"
    },
    "tanggal_pinjam": "2026-10-29",
    "tanggal_kembali": "2026-11-12",
    "tanggal_dikembalikan": null,
    "status": "dipinjam",
    "books": [
      {
        "id": 1,
        "judul": "Clean Code"
      },
      {
        "id": 2,
        "judul": "Laskar Pelangi"
      }
    ]
  }
}
```
- **Response (422 Unprocessable Content):**
```json
{
  "message": "Anggota wajib dipilih. (and 4 more errors)",
  "errors": {
    "member_id": ["Anggota wajib dipilih."],
    "user_id": ["Petugas (user_id) wajib diisi."],
    "tanggal_pinjam": ["Tanggal pinjam wajib diisi."],
    "tanggal_kembali": ["Tanggal kembali wajib diisi."],
    "book_ids": ["Pilih minimal satu buku."]
  }
}
```

---

### 10. `PUT /api/loans/{id}/kembalikan`
- **Deskripsi:** Memproses pengembalian buku, mengubah status menjadi `dikembalikan` dan mengisi `tanggal_dikembalikan` dengan tanggal hari ini.
- **Response (200 OK):**
```json
{
  "data": {
    "id": 1,
    "member": {
      "id": 1,
      "nama": "Ahmad Fadli"
    },
    "petugas": {
      "id": 1,
      "name": "Admin Perpustakaan"
    },
    "tanggal_pinjam": "2026-10-01",
    "tanggal_kembali": "2026-10-15",
    "tanggal_dikembalikan": "2026-10-29",
    "status": "dikembalikan",
    "books": [
      {
        "id": 1,
        "judul": "Clean Code"
      }
    ]
  }
}
```

---

### 11. `GET /api/stats`
- **Deskripsi:** Menghitung dan mengembalikan agregat statistik perpustakaan.
- **Response (200 OK):**
```json
{
  "total_buku": 2,
  "total_anggota": 3,
  "peminjaman_aktif": 1
}
```
