# FixLA - Citizen Reporting Ecosystem

FixLA adalah platform ekosistem pelaporan masalah perkotaan yang dirancang untuk menjembatani komunikasi antara warga dan pemerintah kota Los Angeles (atau kota target lainnya). Platform ini memungkinkan warga untuk melaporkan masalah infrastruktur (seperti jalan berlubang, lampu jalan mati, sampah, dll.) secara langsung melalui aplikasi mobile, yang kemudian dikelola oleh tim admin melalui dashboard pusat.

---

## 🏗️ Arsitektur Proyek

Proyek ini terdiri dari empat komponen utama yang bekerja secara terintegrasi:

1.  **[Backend (API Server)](./backend)**: Dibangun dengan **Laravel 12 (PHP 8.2)** sebagai pusat data dan logika bisnis menggunakan RESTful API.
2.  **[Mobile App](./mobile)**: Dibangun dengan **Flutter** untuk sisi pengguna (warga) untuk melaporkan isu secara langsung dengan dukungan foto dan lokasi GPS.
3.  **[Dashboard Manager](./dashboard)**: Panel admin berbasis **React 19 + Vite + Ant Design** untuk memoderasi laporan, memantau statistik, dan visualisasi peta.
4.  **[Landing Page](./landing-page)**: Situs web pemasaran berbasis **Laravel + Tailwind CSS** untuk memberikan informasi produk dan tautan unduhan.

---

## ✨ Fitur Utama

-   **Pelaporan Instan**: Warga dapat mengirim laporan masalah lengkap dengan foto, deskripsi, dan koordinat lokasi.
-   **Pelacakan Status**: Riwayat status laporan (Pending, In Progress, Resolved) yang dapat dipantau secara real-time.
-   **Gamifikasi & Tugas**: Sistem reward dan progres untuk mendorong partisipasi aktif warga.
-   **Dashboard Visual**: Peta interaktif (Google Maps API) untuk melihat sebaran masalah di kota serta grafik analitik.
-   **Notifikasi Real-time**: Menggunakan sistem notifikasi aplikasi untuk memberitahu pengguna saat laporan mereka ditindaklanjuti.
-   **Sistem Moderasi**: Admin dapat memverifikasi laporan, menugaskan tim lapangan, dan memperbarui status secara efisien.

---

## 🚀 Tech Stack

### Backend
-   **Framework**: Laravel 12
-   **Auth**: Laravel Sanctum (Token based)
-   **Database**: PostgreSQL / MySQL (Support migrations)
-   **Tools**: Artisan, PHPUnit

### Frontend (Dashboard)
-   **Library Core**: React 19, Vite
-   **UI Framework**: Ant Design (AntD)
-   **State Management**: Zustand
-   **Data Fetching**: TanStack Query (React Query)
-   **Charts/Maps**: Recharts, React Google Maps API

### Mobile
-   **Framework**: Flutter
-   **Language**: Dart
-   **Features**: Geolocator, Camera Integration, State Management

### Landing Page
-   **Framework**: Laravel
-   **Styling**: Tailwind CSS

---

## 🛠️ Cara Menjalankan Proyek

Pastikan Anda telah menginstal PHP >= 8.2, Node.js, Composer, dan Flutter SDK.

### 1. Persiapan Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# Sesuaikan DB_DATABASE di .env
php artisan migrate --seed
php artisan serve
```

### 2. Persiapan Dashboard
```bash
cd dashboard
npm install
npm run dev
```

### 3. Persiapan Mobile
```bash
cd mobile
flutter pub get
flutter run
```

### 4. Persiapan Landing Page
```bash
cd landing-page
composer install
npm install
npm run dev
php artisan serve
```

---

## 📂 Struktur Direktori

```text
FixLA/
├── backend/       # Laravel API & Business Logic
├── dashboard/     # React Admin Panel (Moderatork/Admin)
├── mobile/        # Flutter App (Citizen/User)
├── landing-page/  # Laravel Marketing Website
└── .git/          # Git repository configuration
```

---

## 📝 Kontribusi

1. Fork repositori ini.
2. Buat branch fitur baru (`git checkout -b feature/nama-fitur`).
3. Commit perubahan Anda (`git commit -m 'Menambah fitur X'`).
4. Push ke branch tersebut (`git push origin feature/nama-fitur`).
5. Buat Pull Request.

---

## 📄 Lisensi

Proyek ini berada di bawah lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.
