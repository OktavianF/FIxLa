# FixLA Landing Page

Landing page publik FixLA untuk memperkenalkan solusi, fitur utama, dan ajakan adopsi aplikasi ke masyarakat.

## 1. Tech Stack
- Laravel (Blade)
- Vite
- TailwindCSS

## 2. Menjalankan Lokal
```bash
cd landing-page
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install --ignore-scripts
npm run dev
php artisan serve
```

Akses: `http://localhost:8000`

## 3. Struktur Relevan
- `routes/web.php` — route web landing page
- `resources/views/welcome.blade.php` — konten utama landing page
- `resources/css` + `resources/js` — asset frontend

## 4. Skrip Utama
```bash
composer test
npm run dev
npm run build
```

## 5. Tujuan Halaman
- Menjelaskan value proposition FixLA
- Menampilkan fitur utama platform
- Mendorong instalasi/partisipasi warga

## 6. Catatan Production
- Optimasi asset dengan `npm run build`
- Set `APP_ENV=production`, `APP_DEBUG=false`
- Gunakan reverse proxy (Nginx/Apache) untuk serving aplikasi
