# FixLA Mobile

Aplikasi Flutter untuk warga melaporkan jalan rusak, melihat laporan publik, memantau status laporan pribadi, dan menerima notifikasi progres.

## 1. Tech Stack
- Flutter (Dart 3)
- Riverpod
- Dio (HTTP client)
- GoRouter
- Google Maps Flutter

## 2. Menjalankan Lokal
```bash
cd mobile
flutter pub get
flutter run
```

## 3. Struktur Utama
```text
lib/
├── main.dart
├── screens/
├── services/
├── theme/
└── widgets/
```

## 4. Routing Aplikasi
Rute utama (`main.dart`):
- `/splash`
- `/login`
- `/register`
- `/home`
- `/report/create`
- `/report/:id`
- `/my-reports`
- `/profile`
- `/notifications`

## 5. Integrasi Backend
`ApiService` (`lib/services/api_service.dart`) menggunakan:
- Web: `http://127.0.0.1:8000/api/v1`
- Android emulator: `http://10.0.2.2:8000/api/v1`

Fitur API yang dipakai:
- Auth (`register`, `login`, `logout`, `me`)
- Reports (`reports`, `reports/map`, `reports/{id}`, `my-reports`, create report multipart)
- Notifications (`notifications`, `unread-count`, mark-read)

## 6. Penyimpanan Sesi
- Token disimpan via `SharedPreferences` pada key `auth_token`
- Data user disimpan pada key `user_data`

## 7. Pengujian
```bash
flutter test
```

## 8. Catatan Implementasi
- Pastikan backend aktif di port 8000.
- Untuk build production, audit dan rotasi Google Maps API key yang digunakan aplikasi.
