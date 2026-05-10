# FixLA

Platform pelaporan jalan rusak untuk Kabupaten Lamongan yang terdiri dari:
- **Backend API** (Laravel + Sanctum)
- **Dashboard Admin** (React + Vite)
- **Aplikasi Mobile Warga** (Flutter)
- **Landing Page** (Laravel + Blade + Tailwind)

## 1. Ringkasan Produk
FixLA membantu warga melaporkan kerusakan jalan berbasis lokasi/foto, lalu membantu pemerintah melakukan verifikasi, prioritas, dan pemantauan progres perbaikan secara terukur.

## 2. Arsitektur Sistem
```text
[Mobile Flutter] ----\
                     -> [Backend API Laravel] -> [Database]
[Dashboard React] ---/

[Landing Page Laravel] (kanal informasi publik)
```

## 3. Teknologi Utama
- **Backend**: PHP 8.2+, Laravel 12, Sanctum, Eloquent ORM
- **Dashboard**: React 19, Vite, Ant Design, TanStack Query
- **Mobile**: Flutter (Dart 3), Riverpod, Dio, GoRouter, Google Maps
- **Landing Page**: Laravel + Blade + TailwindCSS

## 4. Struktur Repository
```text
FIxLa/
├── backend/       # REST API dan logika bisnis utama
├── dashboard/     # Portal admin/pemerintah
├── mobile/        # Aplikasi mobile warga
└── landing-page/  # Website promosi/informasi publik
```

## 5. Quick Start (Development)

### 5.1 Backend
```bash
cd /home/runner/work/FIxLa/FIxLa/backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

### 5.2 Dashboard
```bash
cd /home/runner/work/FIxLa/FIxLa/dashboard
npm install
npm run dev
```
Dashboard berjalan di `http://localhost:3000` dan melakukan proxy `/api` ke backend `http://localhost:8000`.

### 5.3 Mobile
```bash
cd /home/runner/work/FIxLa/FIxLa/mobile
flutter pub get
flutter run
```

### 5.4 Landing Page
```bash
cd /home/runner/work/FIxLa/FIxLa/landing-page
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install --ignore-scripts
npm run dev
php artisan serve
```

## 6. Akun Seed (Development)
Data seed backend menyediakan akun berikut:
- **Admin**: `admin@fixla.id` / `admin123`
- **User**: `ahmad@example.com` / `password`
- **User**: `siti@example.com` / `password`

## 7. Domain Fitur
- Registrasi/login warga dan admin
- Pelaporan jalan rusak dengan foto + lokasi
- Pelacakan status laporan (`submitted`, `verified`, `scheduled`, `under_repair`, `completed`)
- Notifikasi status ke pelapor
- Dashboard analitik (overview, heatmap, ranking prioritas, tren bulanan)
- Estimasi biaya perbaikan (aspal vs beton)

## 8. API Ringkas
Base URL: `http://localhost:8000/api/v1`
- Public: `/register`, `/login`, `/reports`, `/reports/map`, `/reports/{id}`
- Authenticated: `/logout`, `/me`, `/reports`, `/my-reports`, `/notifications/*`
- Admin-only: `/admin/dashboard/*`, `/admin/reports/{id}/status`, `/admin/cost-estimation`

Lihat detail endpoint di `backend/README.md`.

## 9. Kualitas & Testing
Per modul:
- Backend: `composer test`
- Dashboard: `npm run build`
- Mobile: `flutter test`
- Landing page: `composer test`

## 10. Catatan Keamanan
- Gunakan `.env` untuk semua kredensial dan URL sensitif.
- Token API menggunakan Laravel Sanctum (Bearer token).
- Untuk production, batasi CORS origin dan nonaktifkan `APP_DEBUG`.
- Rotasi API key Google Maps sebelum deployment production.

## 11. Dokumentasi Modul
- `backend/README.md`
- `dashboard/README.md`
- `mobile/README.md`
- `landing-page/README.md`
