# 🗺️ Panduan Setup Developer: Ekosistem FixLA di Windows

Panduan ini dirancang khusus untuk membantu developer baru mengonfigurasi dan menjalankan seluruh komponen ekosistem **FixLA** di sistem operasi **Windows**. Ekosistem ini terdiri dari:
1. **Backend (API Server)** - berbasis Laravel 12 (PHP 8.2) & SQLite
2. **Dashboard Manager (Admin Panel)** - berbasis React 19 + Vite
3. **Landing Page** - berbasis Laravel + Tailwind CSS
4. **Mobile App (Citizen App)** - berbasis Flutter (Dart)

---

## 🛠️ Langkah 1: Persiapan & Instalasi Software (Prasyarat)

Sebelum memulai, pastikan Anda telah menginstal software-software prasyarat berikut di Windows Anda:

### 1. PHP & Web Server (Rekomendasi: Laragon atau Herd)
Sangat direkomendasikan menggunakan **Laragon** untuk Windows karena sangat ringan, mudah mengaktifkan ekstensi PHP, dan sudah menyertakan MySQL/SQLite serta Composer secara otomatis.
*   **Laragon**: [Unduh Laragon Full](https://laragon.org/download/) (PHP 8.2+, Apache, MySQL, Git, npm/yarn).
*   *Alternatif:* **Laravel Herd for Windows** (super cepat, modern) atau **XAMPP**.

### 2. Composer
Jika Anda tidak menggunakan Laragon/Herd yang sudah menyertakan Composer, silakan instal secara manual:
*   **Composer**: [Unduh Composer-Setup.exe](https://getcomposer.org/download/)

### 3. Node.js (LTS Version)
Dibutuhkan untuk menjalankan server build Vite pada Dashboard React dan Landing Page.
*   **Node.js**: [Unduh Node.js LTS](https://nodejs.org/) (versi 18 atau 20+)

### 4. Flutter SDK & Android Studio
Dibutuhkan untuk menjalankan aplikasi Mobile.
*   **Flutter SDK**: [Unduh Flutter Windows SDK](https://docs.flutter.dev/get-started/install/windows/mobile)
*   **Android Studio**: [Unduh Android Studio](https://developer.android.com/studio) (Pastikan untuk menginstal **Android SDK Platform-Tools** dan membuat sebuah **Virtual Device / Emulator**).

### 5. Git
*   **Git for Windows**: [Unduh Git](https://git-scm.com/)

---

## ⚙️ Langkah 2: Mengaktifkan Ekstensi SQLite di PHP

Ekosistem FixLA secara default menggunakan database **SQLite** agar memudahkan portabilitas antar-developer tanpa perlu melakukan instalasi database engine yang berat seperti PostgreSQL/MySQL.

Anda **wajib** mengaktifkan ekstensi SQLite di Windows:

### Jika Menggunakan Laragon:
1. Klik kanan pada aplikasi Laragon di system tray.
2. Arahkan kursor ke **PHP** ➡️ **Extensions**.
3. Pastikan **pdo_sqlite** dan **sqlite3** telah **dicentang (aktif)**.

### Jika Menggunakan PHP Manual / XAMPP:
1. Buka file `php.ini` Anda (biasanya di folder instalasi PHP Anda, misal `C:\tools\php82\php.ini` atau `C:\xampp\php\php.ini`).
2. Cari baris berikut (tekan `Ctrl + F` untuk mencari):
   ```ini
   ;extension=pdo_sqlite
   ;extension=sqlite3
   ```
3. Hapus tanda titik koma (`;`) di depan kedua baris tersebut untuk mengaktifkannya:
   ```ini
   extension=pdo_sqlite
   extension=sqlite3
   ```
4. Simpan file `php.ini` dan restart terminal/server Anda.

---

## 🖥️ Langkah 3: Setup & Menjalankan Setiap Komponen

Buka aplikasi terminal favorit Anda (Rekomendasi: **PowerShell** atau **Windows Terminal**).

```text
FixLA/
├── backend/       (Port 8000)
├── dashboard/     (Port 3000)
├── landing-page/  (Port 8001 & 5173)
└── mobile/        (Flutter App)
```

---

### 🟢 Bagian A: Backend (Laravel API)
Backend ini bertindak sebagai API server utama yang akan diakses oleh Dashboard dan Aplikasi Mobile.

1. Buka terminal di folder `backend`:
   ```powershell
   cd backend
   ```
2. Instal dependensi PHP menggunakan Composer:
   ```powershell
   composer install
   ```
3. Buat file konfigurasi `.env`:
   ```powershell
   Copy-Item .env.example .env
   ```
4. Hasilkan key enkripsi aplikasi:
   ```powershell
   php artisan key:generate
   ```
5. Buat database SQLite kosong secara otomatis dan jalankan migrasi beserta data awal (seeder):
   ```powershell
   php artisan migrate --seed
   ```
   > **Catatan**: Jika Laravel menanyakan *"Would you like to create the database? (Yes/No)"*, ketik **Yes** lalu tekan Enter.
6. Jalankan server Laravel API:
   ```powershell
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   > **Penting**: Menggunakan `--host=0.0.0.0` sangat penting agar aplikasi Flutter yang berjalan di emulator Android atau HP fisik Anda dapat terhubung ke server Laravel di komputer Windows Anda.

---

### 🔵 Bagian B: Dashboard Manager (React + Vite)
Dashboard ini digunakan oleh admin untuk memantau data laporan kota secara real-time.

1. Buka terminal baru di folder `dashboard`:
   ```powershell
   cd dashboard
   ```
2. Instal dependensi Node.js:
   ```powershell
   npm install
   ```
3. Jalankan server development React:
   ```powershell
   npm run dev
   ```
4. Dashboard akan berjalan di alamat `http://localhost:3000`.
   > **Tips**: Vite telah dikonfigurasi dengan sistem proxy otomatis. Setiap request API ke `/api/v1/...` di dashboard akan langsung diteruskan ke API Server Laravel di `http://localhost:8000/api/v1/...` tanpa ada kendala CORS.

---

### 🟡 Bagian C: Landing Page (Laravel + Tailwind)
Halaman web promosi ekosistem FixLA untuk publik.

1. Buka terminal baru di folder `landing-page`:
   ```powershell
   cd landing-page
   ```
2. Instal dependensi PHP dan Node.js:
   ```powershell
   composer install
   npm install
   ```
3. Buat file konfigurasi `.env`:
   ```powershell
   Copy-Item .env.example .env
   ```
4. Hasilkan key enkripsi aplikasi:
   ```powershell
   php artisan key:generate
   ```
5. Jalankan compiler Tailwind CSS (Vite):
   ```powershell
   npm run dev
   ```
6. Buka terminal terpisah di folder `landing-page` untuk menjalankan server web (gunakan port **8001** agar tidak bentrok dengan server Backend):
   ```powershell
   php artisan serve --port=8001
   ```
7. Landing page dapat diakses di `http://127.0.0.1:8001`.

---

### 📱 Bagian D: Mobile App (Flutter)
Aplikasi mobile untuk warga mengirim laporan dengan dukungan GPS dan kamera.

#### 1. Jalankan Analisis Awal
Pastikan Android SDK dan emulator terdeteksi dengan baik oleh Flutter:
```powershell
cd mobile
flutter doctor
```
*Pastikan tidak ada tanda silang merah `[X]` pada baris Android toolchain.*

#### 2. Sesuaikan Konfigurasi Alamat IP API (Sangat Penting!)
Agar aplikasi mobile dapat melakukan *request* ke backend Laravel, Anda harus mengonfigurasi alamat IP di file:
`mobile/lib/services/api_service.dart`

Buka file tersebut, cari method `baseUrl` (sekitar baris 6-12), dan sesuaikan berdasarkan perangkat yang Anda gunakan untuk debugging:

*   **Kasus 1: Menggunakan Android Emulator bawaan (AVD)**
    Android Emulator menganggap PC lokal Anda berada di alamat IP khusus `10.0.2.2`. Ubah return value menjadi:
    ```dart
    static String get baseUrl {
      if (kIsWeb) {
        return 'http://127.0.0.1:8000/api/v1';
      }
      return 'http://10.0.2.2:8000/api/v1'; // Khusus Android Emulator
    }
    ```

*   **Kasus 2: Menggunakan HP Fisik (Debugging via USB/Wi-Fi)**
    Pastikan PC Windows dan HP fisik Anda terhubung ke jaringan Wi-Fi yang **sama**.
    1. Buka CMD di Windows, ketik `ipconfig` untuk melihat IP lokal PC Anda (contoh: `192.168.1.15`).
    2. Ubah return value menjadi IP tersebut:
       ```dart
       static String get baseUrl {
         if (kIsWeb) {
           return 'http://127.0.0.1:8000/api/v1';
         }
         return 'http://192.168.1.15:8000/api/v1'; // Sesuaikan dengan IP komputer Anda
       }
       ```

#### 3. Jalankan Aplikasi
1. Dapatkan paket dependensi Flutter:
   ```powershell
   flutter pub get
   ```
2. Jalankan aplikasi di perangkat target:
   ```powershell
   flutter run
   ```

---

## 🔍 Troubleshooting (Kendala yang Sering Terjadi di Windows)

### 1. Error: `Database file does not exist` saat migrasi database Laravel
*   **Penyebab**: File database SQLite belum terbuat secara otomatis di beberapa versi Windows.
*   **Solusi**: Buat file kosong secara manual di folder `backend/database/database.sqlite` dan `landing-page/database/database.sqlite`.
    *   Di PowerShell:
        ```powershell
        New-Item -ItemType File -Path database/database.sqlite -Force
        ```
    *   Di Command Prompt (CMD):
        ```cmd
        type nul > database/database.sqlite
        ```
    *   Kemudian ulangi perintah `php artisan migrate --seed`.

### 2. Error: `Connection refused` atau `Timeout` di Aplikasi Mobile
*   **Penyebab**: Windows Defender Firewall memblokir port 8000 dari akses luar (untuk HP fisik), atau salah mengonfigurasi alamat IP di `api_service.dart`.
*   **Solusi**:
    1. Pastikan server Laravel dijalankan dengan `--host=0.0.0.0`.
    2. Nonaktifkan sementara firewall jaringan privat Anda atau buat *Inbound Rule* baru untuk mengizinkan port `8000` di Windows Defender Firewall.
    3. Cek kembali IP di `api_service.dart` agar sesuai dengan instruksi di Langkah 3 Bagian D.

### 3. Error `tflite_flutter` crash pada saat build Android
*   **Penyebab**: Versi NDK tidak sesuai atau membutuhkan SDK minimum.
*   **Solusi**:
    Pastikan Gradle menggunakan konfigurasi yang tepat. File TensorFlow Lite model (`road_damage_model.tflite`) sudah berada di folder `assets/ml/` sehingga aplikasi mobile dapat melakukan klasifikasi kerusakan jalan secara lokal (on-device AI). Jika terjadi masalah build, pastikan Anda telah menyetujui semua lisensi Android dengan perintah:
    ```powershell
    flutter doctor --android-licenses
    ```

---

Selamat berkolaborasi dalam mengembangkan ekosistem **FixLA**! Jika Anda memiliki pertanyaan atau kendala lainnya, silakan diskusikan di grup koordinasi tim developer. 🚀
