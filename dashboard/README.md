# FixLA Dashboard (Admin)

Portal admin/pemerintah untuk memantau laporan jalan rusak, mengelola status, melihat heatmap prioritas, statistik, dan estimasi biaya perbaikan.

## 1. Tech Stack
- React 19
- Vite
- Ant Design
- TanStack React Query
- Recharts
- Google Maps (heatmap)

## 2. Menjalankan Lokal
```bash
cd /home/runner/work/FIxLa/FIxLa/dashboard
npm install
npm run dev
```

Akses: `http://localhost:3000`

## 3. Integrasi API
Konfigurasi ada di `src/api.js`:
- Base API: `/api/v1`
- Dev proxy Vite mengarah ke `http://localhost:8000`
- Token disimpan pada `localStorage` key `fixla_token`

## 4. Autentikasi Dashboard
- Halaman login: `/login`
- Hanya role `admin` yang diperbolehkan masuk dashboard
- Token invalid (`401`) akan auto-clear session dan redirect ke login

## 5. Fitur Halaman
- **Overview**: KPI, grafik distribusi, ranking prioritas
- **Reports**: tabel laporan, detail foto, update status
- **Map View**: heatmap lokasi laporan aktif
- **Priority**: ranking prioritas laporan
- **Cost Estimation**: simulasi biaya aspal vs beton
- **Statistics**: tren bulanan dan ringkasan kinerja

## 6. Skrip
```bash
npm run dev
npm run build
npm run preview
```

## 7. Catatan Penting
- `MapView.jsx` saat ini menggunakan API key Google Maps hardcoded. Untuk standar production, pindahkan ke variabel environment (`VITE_*`).
- Pastikan backend berjalan sebelum menggunakan dashboard.

## 8. Akun Demo Admin
- `admin@fixla.id` / `admin123`
