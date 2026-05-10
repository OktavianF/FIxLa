# FixLA Backend API

Backend utama FixLA berbasis Laravel untuk autentikasi, manajemen laporan jalan rusak, notifikasi pengguna, dashboard analitik, dan estimasi biaya perbaikan.

## 1. Tech Stack
- PHP 8.2+
- Laravel 12
- Laravel Sanctum (token auth)
- Eloquent ORM

## 2. Menjalankan Secara Lokal
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Backend default: `http://localhost:8000`

## 3. Environment Penting
Atur minimal variabel berikut pada `.env`:
- `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `FILESYSTEM_DISK` (untuk upload foto)
- `QUEUE_CONNECTION`

## 4. Autentikasi
Menggunakan **Bearer Token** dari Laravel Sanctum.
- Login/registrasi menghasilkan token.
- Endpoint berproteksi menggunakan middleware `auth:sanctum`.
- Endpoint admin ditambah middleware `AdminMiddleware`.

## 5. Model Domain Utama
- `User` (role: `user` / `admin`)
- `Report` (laporan kerusakan jalan)
- `ReportPhoto` (foto laporan)
- `StatusHistory` (riwayat perubahan status)
- `AppNotification` (notifikasi status)

## 6. Status Laporan
Status yang didukung:
- `submitted`
- `verified`
- `scheduled`
- `under_repair`
- `completed`

## 7. Smart Priority Score
Skor prioritas dihitung pada model `Report`:
- faktor jumlah laporan lokasi serupa
- tingkat kerusakan
- traffic level
- kedekatan fasilitas

Dipakai untuk ranking prioritas pada dashboard admin.

## 8. API Reference (Ringkas)
Base URL: `/api/v1`

### 8.1 Public
| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/register` | Registrasi user |
| POST | `/login` | Login user/admin |
| GET | `/reports` | Daftar laporan publik |
| GET | `/reports/map` | Data laporan untuk peta |
| GET | `/reports/{report}` | Detail laporan |
| GET | `/images/{path}` | Serve file gambar |

### 8.2 Authenticated (Bearer)
| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/logout` | Logout |
| GET | `/me` | Profil user login |
| POST | `/reports` | Buat laporan + foto |
| GET | `/my-reports` | Daftar laporan milik user |
| GET | `/notifications` | Daftar notifikasi |
| GET | `/notifications/unread-count` | Hitung notifikasi belum dibaca |
| PATCH | `/notifications/{notification}/read` | Tandai dibaca |
| PATCH | `/notifications/read-all` | Tandai semua dibaca |

### 8.3 Admin Only
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/admin/dashboard/overview` | KPI ringkas |
| GET | `/admin/dashboard/reports-by-district` | Laporan per kecamatan |
| GET | `/admin/dashboard/damage-distribution` | Distribusi kerusakan |
| GET | `/admin/dashboard/priority-ranking` | Ranking prioritas |
| GET | `/admin/dashboard/heatmap` | Data heatmap |
| GET | `/admin/dashboard/monthly-trend` | Tren bulanan |
| PATCH | `/admin/reports/{report}/status` | Update status laporan |
| POST | `/admin/cost-estimation` | Estimasi biaya perbaikan |

## 9. Validasi Input Penting
- Register: nama, email unik, password min 8 + konfirmasi
- Login: email + password
- Create report: lat/lng valid, `damage_level`, 1–5 foto (jpg/png/jpeg, max 5MB/foto)
- Update status: hanya status yang valid
- Cost estimation: panjang, lebar, jenis kerusakan (`retak`, `berlubang`, `amblas`)

## 10. Database & Seeder
Seeder menyediakan data demo:
- admin: `admin@fixla.id` / `admin123`
- beberapa user dan sample reports/status history/notifikasi

Jalankan:
```bash
php artisan migrate --seed
```

## 11. Operasional Harian
- Jalankan test: `composer test`
- Clear config cache: `php artisan config:clear`
- Debug endpoint cepat: gunakan `php artisan route:list`

## 12. Rekomendasi Production
- Gunakan DB managed (MySQL/PostgreSQL)
- Restrict CORS origin (jangan wildcard)
- Set `APP_DEBUG=false`
- Simpan file upload di object storage (S3-compatible)
- Aktifkan monitoring log dan alerting error rate
