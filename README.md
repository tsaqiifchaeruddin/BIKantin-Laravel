# BI-KANTIN — Kantin Sekolah Online

Aplikasi web kantin **SMK Bina Informatika**. Siswa memesan menu dari HP, kasir memvalidasi
pembayaran, dan admin sekolah mengelola menu serta status pesanan.

Versi web ini melengkapi aplikasi desktop **BI-KANTIN** (NetBeans/Java) dengan alur bisnis
yang sama, tetapi memakai database terpisah: `db_bikantin_web`.

---

## Fitur

### Siswa
- Daftar akun siswa (username, kelas, nomor WhatsApp).
- Login dengan username.
- Melihat menu yang tersedia, filter per kategori (Makanan / Minuman / Snack).
- Memesan menu dengan jumlah porsi 1–20; total dihitung di server.
- Melacak status pesanan — diperbarui otomatis setiap 5 detik.
- Melihat status pembayaran (Belum Lunas / Lunas).

### Kasir / Petugas Pembayaran
- Melihat daftar pesanan yang belum lunas.
- Memvalidasi pembayaran → status berubah menjadi **Lunas**.
- Mencetak **struk** pembayaran (halaman siap cetak).
- Statistik: jumlah belum lunas, pendapatan hari ini, transaksi hari ini.

### Admin Sekolah
- Dashboard ringkasan: menu, pesanan hari ini, pesanan aktif, pendapatan lunas.
- CRUD menu (tambah, edit, hapus, tandai habis/tersedia).
- Memperbarui status pesanan sesuai alur:
  `Menunggu Konfirmasi → Sedang Dimasak → Siap Diambil → Selesai`.
- Melihat riwayat semua pesanan dengan filter status dan pencarian.

### Halaman publik
- Landing page dengan menu hari ini dan penjelasan alur pemesanan.
- Login dan pendaftaran siswa.

---

## Alur status

```
Siswa pesan  →  Menunggu Konfirmasi  →  Sedang Dimasak  →  Siap Diambil  →  Selesai
                       │
                       └── Kasir validasi bayar → status_bayar: Lunas
```

- Status masak diubah oleh **admin**.
- Status bayar diubah oleh **kasir** (admin juga boleh).
- Menu yang sudah punya riwayat pesanan **tidak bisa dihapus** — tandai sebagai habis.

---

## Teknologi

- Laravel 13 (PHP 8.4)
- Blade + Tailwind CSS 4 + Vite
- MariaDB / MySQL (WSL)
- PHPUnit (feature test)

---

## Cara menjalankan

### 1. Prasyarat

- PHP 8.4 + Composer
- Node.js 20+ dan npm
- MariaDB/MySQL (di WSL: `sudo service mariadb start`)

### 2. Siapkan database

```bash
sudo mariadb < database/mysql-setup.sql
```

Script membuat database `db_bikantin_web` dan user `bikantin` / `bikantin`.

Atau buat manual:

```sql
CREATE DATABASE db_bikantin_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bikantin'@'localhost' IDENTIFIED BY 'bikantin';
GRANT ALL PRIVILEGES ON db_bikantin_web.* TO 'bikantin'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Konfigurasi

Salin `.env.example` menjadi `.env` (file `.env` sudah disertakan), lalu sesuaikan:

```dotenv
APP_NAME="BI-KANTIN"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_bikantin_web
DB_USERNAME=bikantin
DB_PASSWORD=bikantin
```

```bash
php artisan key:generate   # hanya jika APP_KEY masih kosong
```

### 4. Migrasi + data contoh

```bash
php artisan migrate:fresh --seed
```

### 5. Build aset & jalankan

```bash
npm install
npm run build
php artisan serve
```

Buka <http://localhost:8000>.

Untuk pengembangan dengan hot reload:

```bash
npm run dev
php artisan serve
```

> **Catatan WSL:** server `php artisan serve` cukup dijalankan di WSL.
> Windows bisa membuka `http://localhost:8000` karena port diteruskan otomatis.

---

## Akun demo

| Peran | Username | Password |
| --- | --- | --- |
| Admin sekolah | `admin_kantin` | `admin123` |
| Kasir | `kasir_kantin` | `kasir123` |
| Siswa | `budi_siswa` | `123` |
| Siswa | `siti_siswa` | `123` |

Role otomatis menentukan halaman setelah login:

- `admin` → `/admin`
- `kasir` → `/kasir`
- `siswa` → `/siswa`

---

## Struktur penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── Auth/            # Login + register siswa
│   │   ├── Siswa/           # Menu, pesanan, polling status
│   │   ├── Admin/           # Dashboard, menu, pesanan
│   │   └── Kasir/           # Pembayaran + struk
│   └── Middleware/EnsureRole.php
└── Models/
    ├── User.php
    ├── Menu.php
    └── Pesanan.php

database/
├── migrations/
├── seeders/DatabaseSeeder.php
└── mysql-setup.sql

resources/views/
├── layouts/app.blade.php    # navigasi + toast
├── layouts/auth.blade.php
├── home.blade.php
├── auth/
├── siswa/
├── admin/
├── kasir/
└── components/status.blade.php
```

---

## Pengujian

```bash
php artisan test
```

Mencakup: login per role, pembatasan akses, pembuatan pesanan (total dihitung server),
menolak menu habis, CRUD menu, larangan hapus menu yang punya riwayat, validasi
pembayaran, dan alur status pesanan.

---

## Logo sekolah

Logo diambil **otomatis** dari `public/images/logo-bi.png` (boleh juga `.jpg`, `.jpeg`,
`.webp`, atau `.svg`). Tidak perlu mengubah kode sama sekali.

- **Ganti logo** = timpa file `public/images/logo-bi.png`.
- **Hapus logo** = hapus/rename file tersebut; kotak gradien bertuliskan "BI" akan tampil.
- Disarankan: gambar persegi, minimal 256×256 px, latar transparan.

Ukuran dan posisi sudah diatur oleh komponen `<x-logo>`: header `h-9 w-9`, panel login
`h-11 w-11`, gambar memakai `object-fit: contain` di dalam slot. Jadi foto logo tidak
akan kebesaran atau meluber, berapa pun resolusi aslinya.

---

## Development cepat (WSL + sync)

Menjalankan Laravel langsung dari `C:` (`/mnt/c`) terasa lambat. Karena itu ada salinan
di `/home/chaes/proyek/bi-kantin-wsl` yang dipakai untuk `php artisan serve`, plus skrip
sinkronisasi:

```bash
~/proyek/BIKantin-sync.sh            # C: -> WSL, sekali jalan
~/proyek/BIKantin-sync.sh --watch    # pantau perubahan di C: (cek tiap 2 detik)
~/proyek/BIKantin-sync.sh --reverse  # WSL -> C: (sebelum commit/push ke GitHub)
```

- Edit kode di `C:\Sekolah\XI-GANJIL\Coding\PW2\BIKantin` seperti biasa.
- Jalankan `--watch` di terminal WSL; setiap perubahan tersalin + aset di-build ulang.
- Server tidak perlu di-restart, cukup refresh browser.
- Script tidak menyalin `vendor`, `node_modules`, atau `storage` (aman untuk kedua sisi).

---

## Catatan

- Password disimpan sebagai hash (`bcrypt`).
- Aplikasi ini memakai database **terpisah** (`db_bikantin_web`) dari aplikasi desktop
  BI-KANTIN (`db_bikantin`) karena versi desktop menyimpan password plain text sesuai
  kebutuhan tugas.
