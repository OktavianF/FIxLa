import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

class FaqScreen extends StatelessWidget {
  const FaqScreen({super.key});

  final List<Map<String, String>> faqs = const [
    {
      'q': 'Apa itu FixLA?',
      'a': 'FixLA adalah platform pelaporan infrastruktur kota yang memungkinkan masyarakat untuk melaporkan kerusakan jalan, lampu penerangan, dan fasilitas publik lainnya secara mudah dan transparan.'
    },
    {
      'q': 'Bagaimana cara melaporkan kerusakan?',
      'a': 'Pilih menu "Beranda" atau klik tombol "Tambah Foto" (+) di bagian tengah bawah layar. Kemudian ambil foto kerusakan, lengkapi detail lokasi dan deskripsi, lalu tekan kirim.'
    },
    {
      'q': 'Apakah saya perlu mengaktifkan GPS?',
      'a': 'Ya, GPS (Lokasi) sangat dibutuhkan agar sistem dapat memetakan secara akurat di mana titik kerusakan tersebut berada, sehingga tim perbaikan dapat langsung menuju ke lokasi.'
    },
    {
      'q': 'Berapa lama laporan saya akan ditangani?',
      'a': 'Waktu penanganan bervariasi tergantung pada tingkat keparahan (Priority Score) yang dihitung oleh sistem AI kami. Laporan dengan status "Berat" dan berada di jalur utama akan diprioritaskan.'
    },
    {
      'q': 'Bagaimana cara melihat status laporan saya?',
      'a': 'Anda dapat melihat status laporan yang telah Anda buat pada menu "Laporan Saya" (ikon dokumen) di bagian bawah layar.'
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Bantuan & FAQ'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 24),
        itemCount: faqs.length,
        itemBuilder: (context, index) {
          final faq = faqs[index];
          return Card(
            elevation: 0,
            margin: const EdgeInsets.only(bottom: 12),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: BorderSide(color: AppTheme.neutral300.withValues(alpha: 0.5)),
            ),
            child: ExpansionTile(
              title: Text(
                faq['q']!,
                style: const TextStyle(
                  fontWeight: FontWeight.w600,
                  fontSize: 15,
                  color: AppTheme.neutral900,
                ),
              ),
              childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
              children: [
                Text(
                  faq['a']!,
                  style: const TextStyle(
                    color: AppTheme.neutral700,
                    fontSize: 14,
                    height: 1.5,
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
