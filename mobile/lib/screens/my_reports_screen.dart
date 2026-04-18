import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';

class MyReportsScreen extends StatefulWidget {
  const MyReportsScreen({super.key});

  @override
  State<MyReportsScreen> createState() => _MyReportsScreenState();
}

class _MyReportsScreenState extends State<MyReportsScreen> {
  List<dynamic> _reports = [];
  bool _loading = true;
  String? _filterStatus;

  @override
  void initState() {
    super.initState();
    _loadReports();
  }

  Future<void> _loadReports() async {
    setState(() => _loading = true);
    try {
      final api = ApiService();
      final res = await api.getMyReports(status: _filterStatus);
      setState(() {
        _reports = res.data['data']['data'] ?? [];
        _loading = false;
      });
    } catch (e) {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.neutral100,
      extendBodyBehindAppBar: true,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.white),
          onPressed: () => context.pop(),
        ),
        actions: [
          PopupMenuButton<String?>(
            icon: const Icon(Icons.filter_list_rounded, color: Colors.white),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            onSelected: (v) {
              setState(() => _filterStatus = v);
              _loadReports();
            },
            itemBuilder: (_) => [
              const PopupMenuItem(value: null, child: Text('Semua', style: TextStyle(fontWeight: FontWeight.w600))),
              const PopupMenuItem(value: 'submitted', child: Text('Submitted', style: TextStyle(fontWeight: FontWeight.w600))),
              const PopupMenuItem(value: 'verified', child: Text('Verified', style: TextStyle(fontWeight: FontWeight.w600))),
              const PopupMenuItem(value: 'scheduled', child: Text('Scheduled', style: TextStyle(fontWeight: FontWeight.w600))),
              const PopupMenuItem(value: 'under_repair', child: Text('Under Repair', style: TextStyle(fontWeight: FontWeight.w600))),
              const PopupMenuItem(value: 'completed', child: Text('Completed', style: TextStyle(fontWeight: FontWeight.w600))),
            ],
          ),
        ],
      ),
      body: Column(
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.only(top: 100, bottom: 32, left: 24, right: 24),
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [AppTheme.primary, AppTheme.primaryDark],
              ),
              borderRadius: BorderRadius.only(bottomLeft: Radius.circular(40), bottomRight: Radius.circular(40)),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Laporan Saya', style: TextStyle(fontSize: 28, fontWeight: FontWeight.w800, color: Colors.white, letterSpacing: -0.5)),
                const SizedBox(height: 8),
                Text('Riwayat interaksi Anda untuk kota tercinta.', style: TextStyle(color: Colors.white.withValues(alpha: 0.8), fontSize: 15)),
              ],
            ),
          ),
          Expanded(
            child: RefreshIndicator(
              onRefresh: _loadReports,
              child: _loading
                  ? const Center(child: CircularProgressIndicator())
                  : _reports.isEmpty
                      ? const Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.inbox_rounded, size: 64, color: AppTheme.neutral300),
                              SizedBox(height: 12),
                              Text('Belum ada laporan', style: TextStyle(color: AppTheme.neutral700, fontWeight: FontWeight.w600, fontSize: 16)),
                            ],
                          ),
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.all(24),
                          itemCount: _reports.length,
                          itemBuilder: (_, i) => _buildReportCard(_reports[i]),
                        ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildReportCard(Map<String, dynamic> report) {
    final photos = report['photos'] as List<dynamic>?;
    final hasPhoto = photos != null && photos.isNotEmpty;
    final photoUrl = hasPhoto ? photos[0]['url']?.toString() : null;

    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: InkWell(
        onTap: () => context.push('/report/${report['id']}'),
        borderRadius: BorderRadius.circular(20),
        child: Ink(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            border: Border.all(color: AppTheme.neutral300.withValues(alpha: 0.5)),
            boxShadow: [
              BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 10, offset: const Offset(0, 4)),
            ],
          ),
          child: Row(
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(16),
                child: photoUrl != null
                    ? CachedNetworkImage(
                        imageUrl: photoUrl,
                        width: 64,
                        height: 64,
                        fit: BoxFit.cover,
                        placeholder: (_, __) => Container(
                          width: 64, height: 64,
                          color: AppTheme.neutral300.withValues(alpha: 0.3),
                          child: const Center(child: SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2))),
                        ),
                        errorWidget: (_, __, ___) => Container(
                          width: 64, height: 64,
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(16),
                            color: AppTheme.getDamageColor(report['damage_level'] ?? '').withValues(alpha: 0.1),
                          ),
                          child: Icon(Icons.warning_amber_rounded, color: AppTheme.getDamageColor(report['damage_level'] ?? ''), size: 32),
                        ),
                      )
                    : Container(
                        width: 64, height: 64,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(16),
                          color: AppTheme.getDamageColor(report['damage_level'] ?? '').withValues(alpha: 0.1),
                        ),
                        child: Icon(Icons.warning_amber_rounded, color: AppTheme.getDamageColor(report['damage_level'] ?? ''), size: 32),
                      ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      report['address'] ?? 'Lokasi tidak diketahui',
                      style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 15, color: AppTheme.neutral900),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 6),
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: AppTheme.getStatusColor(report['status'] ?? '').withValues(alpha: 0.15),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Text(
                            AppTheme.getStatusLabel(report['status'] ?? ''),
                            style: TextStyle(fontSize: 11, color: AppTheme.getStatusColor(report['status'] ?? ''), fontWeight: FontWeight.w600),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: AppTheme.getDamageColor(report['damage_level'] ?? '').withValues(alpha: 0.15),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Text(
                            report['damage_level'] ?? '',
                            style: TextStyle(fontSize: 11, color: AppTheme.getDamageColor(report['damage_level'] ?? ''), fontWeight: FontWeight.w600),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 6),
                    Text(_formatDate(report['created_at']), style: const TextStyle(fontSize: 11, color: AppTheme.neutral700, fontWeight: FontWeight.w500)),
                  ],
                ),
              ),
              const SizedBox(width: 8),
              const Icon(Icons.arrow_forward_ios_rounded, color: AppTheme.neutral300, size: 16),
            ],
          ),
        ),
      ),
    );
  }

  String _formatDate(String? date) {
    if (date == null) return '';
    try {
      final d = DateTime.parse(date);
      return '${d.day}/${d.month}/${d.year}';
    } catch (_) {
      return '';
    }
  }
}
