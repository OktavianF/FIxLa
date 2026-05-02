import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';

class ReportDetailScreen extends StatefulWidget {
  final int reportId;
  const ReportDetailScreen({super.key, required this.reportId});

  @override
  State<ReportDetailScreen> createState() => _ReportDetailScreenState();
}

class _ReportDetailScreenState extends State<ReportDetailScreen> {
  Map<String, dynamic>? _report;
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _loadReport();
  }

  Future<void> _loadReport() async {
    try {
      final api = ApiService();
      final res = await api.getReport(widget.reportId);
      setState(() {
        _report = res.data['data'];
        _loading = false;
      });
    } catch (e) {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }
    if (_report == null) {
      return const Scaffold(body: Center(child: Text('Laporan tidak ditemukan')));
    }

    final photos = _report!['photos'] as List?;
    final hasPhotos = photos != null && photos.isNotEmpty;

    return Scaffold(
      backgroundColor: Colors.white,
      body: CustomScrollView(
        physics: const BouncingScrollPhysics(),
        slivers: [
          SliverAppBar(
            expandedHeight: 280,
            pinned: true,
            backgroundColor: AppTheme.primary,
            iconTheme: const IconThemeData(color: Colors.white),
            title: const Text('', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
            flexibleSpace: FlexibleSpaceBar(
              background: hasPhotos
                  ? Stack(
                      fit: StackFit.expand,
                      children: [
                        PageView.builder(
                          itemCount: photos.length,
                          itemBuilder: (_, i) {
                            final url = photos[i]['url'];
                            return CachedNetworkImage(
                              imageUrl: url ?? '',
                              fit: BoxFit.cover,
                              placeholder: (_, __) => Container(color: Colors.grey[200], child: const Center(child: CircularProgressIndicator())),
                              errorWidget: (_, __, ___) => Container(color: Colors.grey[200], child: const Icon(Icons.broken_image, size: 48)),
                            );
                          },
                        ),
                        // Gradient Overlay for Header Clarity
                        Positioned(
                          top: 0,
                          left: 0,
                          right: 0,
                          height: 100,
                          child: DecoratedBox(
                            decoration: BoxDecoration(
                              gradient: LinearGradient(
                                begin: Alignment.topCenter,
                                end: Alignment.bottomCenter,
                                colors: [Colors.black.withValues(alpha: 0.5), Colors.transparent],
                              ),
                            ),
                          ),
                        ),
                      ],
                    )
                  : Container(
                      color: AppTheme.primaryDark,
                      child: const Center(child: Icon(Icons.image_not_supported, size: 64, color: Colors.white54)),
                    ),
            ),
          ),
          SliverFillRemaining(
            hasScrollBody: false,
            child: Transform.translate(
              offset: const Offset(0, -32), // Naik lebih tinggi
              child: Container(
                decoration: const BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.only(topLeft: Radius.circular(32), topRight: Radius.circular(32)),
                ),
                child: Padding(
                  padding: const EdgeInsets.only(left: 24, right: 24, top: 16, bottom: 32),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Indikator garis atas (Handle Pull)
                      Center(
                        child: Container(
                          width: 48,
                          height: 5,
                          decoration: BoxDecoration(
                            color: Colors.grey[300],
                            borderRadius: BorderRadius.circular(10),
                          ),
                        ),
                      ),
                      const SizedBox(height: 32), // Memberi jarak lega dengan foto

                      // Judul Header (Laporan #ID)
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            'Laporan #${widget.reportId}',
                            style: const TextStyle(fontSize: 24, fontWeight: FontWeight.w900, letterSpacing: -0.5),
                          ),
                          _badge(
                            AppTheme.getStatusLabel(_report!['status'] ?? ''),
                            AppTheme.getStatusColor(_report!['status'] ?? ''),
                            null, // Icon dihilangkan agar badge status terlihat clean (pill shape)
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      
                      // Baris Damage Label
                      Wrap(
                        spacing: 8,
                        runSpacing: 8,
                        children: [
                          _badge(
                            'Kerusakan ${(_report!['damage_level'] ?? '').toString().toUpperCase()}',
                            AppTheme.getDamageColor(_report!['damage_level'] ?? ''),
                            Icons.warning_amber_rounded,
                          ),
                        ],
                      ),
                      const SizedBox(height: 28),

                      // Lokasi
                      const Text('LOKASI', style: TextStyle(color: Colors.grey, fontSize: 12, fontWeight: FontWeight.bold, letterSpacing: 1)),
                      const SizedBox(height: 8),
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Icon(Icons.location_on_rounded, size: 24, color: AppTheme.primary),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  _report!['address'] ?? 'Lokasi tidak diketahui',
                                  style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600, height: 1.4),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  'Kec. ${_report!['district'] ?? '-'}',
                                  style: TextStyle(color: Colors.grey[600], fontSize: 14),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 28),
                      const Divider(height: 1, color: Color(0xFFEEEEEE)),
                      const SizedBox(height: 24),

                      // Kotak Statistik Dinamis (Icon menyamping, biar tidak kepanjangan)
                      Row(
                        children: [
                          Expanded(child: _statBox("Prioritas", _report!['priority_score']?.toString() ?? '-', Icons.stars_rounded)),
                          const SizedBox(width: 12),
                          Expanded(child: _statBox("Pelapor", '${_report!['report_count'] ?? 0}', Icons.people_alt_rounded)),
                        ],
                      ),
                      const SizedBox(height: 16),
                      
                      // Kartu Info User & Tanggal
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
                        decoration: BoxDecoration(
                          color: AppTheme.neutral100, // Warna bg lebih padu
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: Colors.grey[200]!),
                        ),
                        child: Column(
                          children: [
                            _infoRow(Icons.person_rounded, 'Pelapor', _report!['user']?['name'] ?? '-'),
                            const Padding(padding: EdgeInsets.symmetric(vertical: 12), child: Divider(height: 1, color: Color(0xFFE0E0E0))),
                            _infoRow(Icons.calendar_month_rounded, 'Tanggal', _formatDate(_report!['created_at'])),
                          ],
                        ),
                      ),
                      const SizedBox(height: 32),

                      // Deskripsi
                      if (_report!['description'] != null && _report!['description'].toString().isNotEmpty) ...[
                        const Text('DESKRIPSI', style: TextStyle(color: Colors.grey, fontSize: 12, fontWeight: FontWeight.bold, letterSpacing: 1)),
                        const SizedBox(height: 12),
                        Text(
                          _report!['description'],
                          style: const TextStyle(fontSize: 15, height: 1.6, color: Colors.black87),
                        ),
                        const SizedBox(height: 32),
                      ],

                      // Timeline / Riwayat Status
                      if ((_report!['status_histories'] as List?)?.isNotEmpty == true) ...[
                        const Text('RIWAYAT STATUS', style: TextStyle(color: Colors.grey, fontSize: 12, fontWeight: FontWeight.bold, letterSpacing: 1)),
                        const SizedBox(height: 16),
                        Container(
                          padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 16),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: Colors.grey[200]!),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: (_report!['status_histories'] as List)
                                .map((h) => _timelineItem(h, isLast: h == (_report!['status_histories'] as List).last))
                                .toList(),
                          ),
                        ),
                        const SizedBox(height: 48), // Spasi paling bawah biar enak di-scroll
                      ],
                    ],
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _badge(String label, Color color, IconData? icon) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: color.withValues(alpha: 0.3)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (icon != null) ...[Icon(icon, size: 16, color: color), const SizedBox(width: 6)],
          Text(label, style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: color, letterSpacing: 0.3)),
        ],
      ),
    );
  }

  Widget _statBox(String title, String value, IconData icon) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
      decoration: BoxDecoration(
        color: AppTheme.primary.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppTheme.primary.withValues(alpha: 0.1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 28, color: AppTheme.primary),
          const SizedBox(height: 12),
          Text(value, style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: AppTheme.primaryDark)),
          const SizedBox(height: 4),
          Text(title, style: TextStyle(fontSize: 13, color: Colors.grey[700], fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  Widget _infoRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, size: 20, color: Colors.grey[500]),
        const SizedBox(width: 10),
        Text(label, style: TextStyle(color: Colors.grey[600], fontSize: 14)),
        const Spacer(),
        Flexible(
          child: Text(
            value,
            textAlign: TextAlign.right,
            style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14),
          ),
        ),
      ],
    );
  }

  Widget _timelineItem(Map<String, dynamic> history, {bool isLast = false}) {
    final toStatus = history['to_status'] ?? '';
    return IntrinsicHeight(
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Column(
            children: [
              Container(
                width: 32,
                height: 32,
                decoration: BoxDecoration(
                  color: AppTheme.getStatusColor(toStatus).withValues(alpha: 0.15),
                  shape: BoxShape.circle,
                ),
                child: Center(
                  child: Container(
                    width: 14,
                    height: 14,
                    decoration: BoxDecoration(
                      color: AppTheme.getStatusColor(toStatus),
                      shape: BoxShape.circle,
                    ),
                  ),
                ),
              ),
              if (!isLast) Expanded(child: Container(width: 2, color: Colors.grey[200])),
            ],
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Padding(
              padding: const EdgeInsets.only(bottom: 28),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(AppTheme.getStatusLabel(toStatus), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                  const SizedBox(height: 6),
                  Row(
                    children: [
                      Icon(Icons.access_time_rounded, size: 14, color: Colors.grey[500]),
                      const SizedBox(width: 4),
                      Text(
                        _formatDate(history['created_at']),
                        style: TextStyle(fontSize: 13, color: Colors.grey[500], fontWeight: FontWeight.w500),
                      ),
                    ],
                  ),
                  if (history['notes'] != null && history['notes'].toString().isNotEmpty) ...[
                    const SizedBox(height: 10),
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.grey[50], // AppTheme.neutral100
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: Colors.grey[200]!),
                      ),
                      child: Text(history['notes'], style: TextStyle(fontSize: 14, color: Colors.grey[700], height: 1.5)),
                    ),
                  ],
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  String _formatDate(String? date) {
    if (date == null) return '-';
    try {
      final d = DateTime.parse(date);
      return '${d.day}/${d.month}/${d.year} ${d.hour}:${d.minute.toString().padLeft(2, '0')}';
    } catch (_) {
      return date;
    }
  }
}
