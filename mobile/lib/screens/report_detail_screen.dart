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
    return Scaffold(
      appBar: AppBar(title: Text('Laporan #${widget.reportId}')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _report == null
              ? const Center(child: Text('Laporan tidak ditemukan'))
              : SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Photos
                      if ((_report!['photos'] as List?)?.isNotEmpty == true)
                        SizedBox(
                          height: 220,
                          child: PageView.builder(
                            itemCount: (_report!['photos'] as List).length,
                            itemBuilder: (_, i) {
                              final url = _report!['photos'][i]['url'];
                              return CachedNetworkImage(
                                imageUrl: url ?? '',
                                width: double.infinity,
                                height: 220,
                                fit: BoxFit.cover,
                                placeholder: (_, __) => Container(color: Colors.grey[200], child: const Center(child: CircularProgressIndicator())),
                                errorWidget: (_, __, ___) => Container(color: Colors.grey[200], child: const Icon(Icons.broken_image, size: 48)),
                              );
                            },
                          ),
                        )
                      else
                        Container(
                          height: 180,
                          color: Colors.grey[200],
                          child: const Center(child: Icon(Icons.image_not_supported, size: 48, color: Colors.grey)),
                        ),

                      Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Status and damage badges
                            Row(
                              children: [
                                _badge(
                                  AppTheme.getStatusLabel(_report!['status'] ?? ''),
                                  AppTheme.getStatusColor(_report!['status'] ?? ''),
                                  AppTheme.getStatusIcon(_report!['status'] ?? ''),
                                ),
                                const SizedBox(width: 8),
                                _badge(
                                  (_report!['damage_level'] ?? '').toString().toUpperCase(),
                                  AppTheme.getDamageColor(_report!['damage_level'] ?? ''),
                                  null,
                                ),
                              ],
                            ),
                            const SizedBox(height: 16),

                            // Address
                            Text(
                              _report!['address'] ?? 'Lokasi tidak diketahui',
                              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w700),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              'Kecamatan ${_report!['district'] ?? '-'}',
                              style: TextStyle(color: Colors.grey[600]),
                            ),
                            const SizedBox(height: 16),

                            // Description
                            if (_report!['description'] != null) ...[
                              const Text('Deskripsi', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
                              const SizedBox(height: 4),
                              Text(_report!['description']),
                              const SizedBox(height: 16),
                            ],

                            // Info Cards
                            _infoRow('Skor Prioritas', _report!['priority_score']?.toString() ?? '-'),
                            _infoRow('Jumlah Laporan', '${_report!['report_count'] ?? 0}×'),
                            _infoRow('Pelapor', _report!['user']?['name'] ?? '-'),
                            _infoRow('Tanggal', _formatDate(_report!['created_at'])),

                            const SizedBox(height: 20),

                            // Status Timeline
                            if ((_report!['status_histories'] as List?)?.isNotEmpty == true) ...[
                              const Text('Riwayat Status', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
                              const SizedBox(height: 12),
                              ...(_report!['status_histories'] as List).map((h) => _timelineItem(h)),
                            ],
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
    );
  }

  Widget _badge(String label, Color color, IconData? icon) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(10),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (icon != null) ...[Icon(icon, size: 14, color: color), const SizedBox(width: 4)],
          Text(label, style: TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: color)),
        ],
      ),
    );
  }

  Widget _infoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey[600])),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  Widget _timelineItem(Map<String, dynamic> history) {
    final toStatus = history['to_status'] ?? '';
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Column(
            children: [
              Container(
                width: 24,
                height: 24,
                decoration: BoxDecoration(
                  color: AppTheme.getStatusColor(toStatus),
                  shape: BoxShape.circle,
                ),
                child: Icon(AppTheme.getStatusIcon(toStatus), color: Colors.white, size: 14),
              ),
              Container(width: 2, height: 24, color: Colors.grey[300]),
            ],
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(AppTheme.getStatusLabel(toStatus), style: const TextStyle(fontWeight: FontWeight.w600)),
                Text(
                  _formatDate(history['created_at']),
                  style: TextStyle(fontSize: 12, color: Colors.grey[500]),
                ),
                if (history['notes'] != null)
                  Text(history['notes'], style: TextStyle(fontSize: 13, color: Colors.grey[600])),
              ],
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
