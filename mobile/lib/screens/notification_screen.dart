import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';

class NotificationScreen extends StatefulWidget {
  const NotificationScreen({super.key});

  @override
  State<NotificationScreen> createState() => _NotificationScreenState();
}

class _NotificationScreenState extends State<NotificationScreen> {
  List<dynamic> _notifications = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final api = ApiService();
      final res = await api.getNotifications();
      setState(() {
        _notifications = res.data['data']['data'] ?? [];
        _loading = false;
      });
    } catch (e) {
      setState(() => _loading = false);
    }
  }

  Future<void> _markAllRead() async {
    try {
      final api = ApiService();
      await api.markAllAsRead();
      _load();
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Notifikasi'),
        actions: [
          TextButton(
            onPressed: _markAllRead,
            child: const Text('Baca Semua', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _load,
        child: _loading
            ? const Center(child: CircularProgressIndicator())
            : _notifications.isEmpty
                ? const Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.notifications_off_outlined, size: 64, color: Colors.grey),
                        SizedBox(height: 12),
                        Text('Belum ada notifikasi', style: TextStyle(color: Colors.grey)),
                      ],
                    ),
                  )
                : ListView.separated(
                    itemCount: _notifications.length,
                    separatorBuilder: (_, __) => const Divider(height: 1),
                    itemBuilder: (_, i) {
                      final n = _notifications[i];
                      final isRead = n['is_read'] == true;
                      return ListTile(
                        leading: CircleAvatar(
                          backgroundColor: isRead ? Colors.grey[200] : AppTheme.primary.withValues(alpha: 0.15),
                          child: Icon(
                            n['type'] == 'status_update' ? Icons.update : Icons.notifications,
                            color: isRead ? Colors.grey : AppTheme.primary,
                            size: 20,
                          ),
                        ),
                        title: Text(
                          n['title'] ?? '',
                          style: TextStyle(fontWeight: isRead ? FontWeight.normal : FontWeight.w600, fontSize: 14),
                        ),
                        subtitle: Text(n['message'] ?? '', style: const TextStyle(fontSize: 13), maxLines: 2, overflow: TextOverflow.ellipsis),
                        tileColor: isRead ? null : AppTheme.primary.withValues(alpha: 0.03),
                        onTap: () {
                          if (n['report_id'] != null) {
                            context.push('/report/${n['report_id']}');
                          }
                          if (!isRead) {
                            ApiService().markAsRead(n['id']);
                          }
                        },
                      );
                    },
                  ),
      ),
    );
  }
}
