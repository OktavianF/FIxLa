import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';
import '../widgets/glass_container.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  String _userName = '';
  List<dynamic> _recentReports = [];
  List<dynamic> _myReports = [];
  List<dynamic> _mapReports = [];
  bool _loading = true;
  bool _myLoading = true;

  @override
  void initState() {
    super.initState();
    _loadUser();
    _loadReports();
    _loadMyReports();
    _loadMapReports();
  }

  Future<void> _loadUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userData = prefs.getString('user_data');
    if (userData != null) {
      final user = jsonDecode(userData);
      setState(() => _userName = user['name'] ?? '');
    }
  }

  Future<void> _loadReports() async {
    try {
      final api = ApiService();
      final res = await api.getReports(params: {'per_page': '5'});
      setState(() {
        _recentReports = res.data['data']['data'] ?? [];
        _loading = false;
      });
    } catch (e) {
      setState(() => _loading = false);
    }
  }

  Future<void> _loadMyReports() async {
    try {
      final api = ApiService();
      final res = await api.getMyReports();
      setState(() {
        _myReports = res.data['data']['data'] ?? [];
        _myLoading = false;
      });
    } catch (e) {
      setState(() => _myLoading = false);
    }
  }

  Future<void> _loadMapReports() async {
    try {
      final api = ApiService();
      final res = await api.getMapReports();
      setState(() {
        _mapReports = res.data['data'] ?? [];
      });
    } catch (_) {}
  }

  Future<void> _navigateToCreateReport() async {
    final result = await context.push('/report/create');
    if (result == true) {
      _loadReports();
      _loadMyReports();
      _loadMapReports();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBody: true,
      extendBodyBehindAppBar: true,
      backgroundColor: AppTheme.neutral100,
      body: IndexedStack(
        index: _currentIndex,
        children: [
          _buildHomeTab(),
          _buildMapTab(),
          const SizedBox(), // Placeholder for FAB
          _buildReportsTab(),
          _buildProfileTab(),
        ],
      ),
      bottomNavigationBar: _buildBottomNav(),
    );
  }

  Widget _buildBottomNav() {
    return SafeArea(
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
        child: GlassContainer(
          borderRadius: BorderRadius.circular(36),
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 8),
          color: Colors.white,
          opacity: 0.85,
          border: Border.all(color: Colors.white, width: 1.5),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              _navItem(Icons.home_filled, Icons.home_outlined, 'Beranda', 0),
              _navItem(Icons.map_rounded, Icons.map_outlined, 'Peta', 1),
              GestureDetector(
                onTap: _navigateToCreateReport,
                child: Container(
                  height: 56,
                  width: 56,
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(colors: [AppTheme.primary, AppTheme.accent], begin: Alignment.topLeft, end: Alignment.bottomRight),
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(color: AppTheme.primary.withValues(alpha: 0.4), blurRadius: 12, offset: const Offset(0, 4)),
                    ],
                  ),
                  child: const Icon(Icons.add_a_photo_rounded, color: Colors.white, size: 28),
                ),
              ),
              _navItem(Icons.assignment_rounded, Icons.assignment_outlined, 'Laporan', 3),
              _navItem(Icons.person_rounded, Icons.person_outline, 'Profil', 4),
            ],
          ),
        ),
      ),
    );
  }

  Widget _navItem(IconData activeIcon, IconData inactiveIcon, String label, int index) {
    final isActive = _currentIndex == index;
    return GestureDetector(
      onTap: () => setState(() => _currentIndex = index),
      behavior: HitTestBehavior.opaque,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(isActive ? activeIcon : inactiveIcon, color: isActive ? AppTheme.primary : AppTheme.neutral300, size: 26),
            const SizedBox(height: 4),
            Text(label, style: TextStyle(fontSize: 10, fontWeight: isActive ? FontWeight.w800 : FontWeight.w600, color: isActive ? AppTheme.primary : AppTheme.neutral300)),
          ],
        ),
      ),
    );
  }

  Widget _buildHomeTab() {
    return RefreshIndicator(
      onRefresh: () async {
        await Future.wait([_loadReports(), _loadMyReports(), _loadMapReports()]);
      },
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.only(bottom: 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.fromLTRB(24, 64, 24, 32),
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
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(
                        child: Row(
                          children: [

                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text('Halo, $_userName 👋', style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: Colors.white, letterSpacing: -0.5)),
                                  const SizedBox(height: 2),
                                  Text('Yuk, laporkan jalan rusak di kotamu.', style: TextStyle(color: Colors.white.withValues(alpha: 0.8), fontSize: 13)),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                      GlassContainer(
                        blur: 20, opacity: 0.15, borderRadius: BorderRadius.circular(16), 
                        padding: const EdgeInsets.all(10), border: Border.all(color: Colors.white.withValues(alpha: 0.1)),
                        child: GestureDetector(
                           onTap: () => context.push('/notifications'),
                           child: const Icon(Icons.notifications_outlined, color: Colors.white, size: 28),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 36),
                  GlassContainer(
                    blur: 15, opacity: 0.15, borderRadius: BorderRadius.circular(24),
                    padding: const EdgeInsets.all(20), border: Border.all(color: Colors.white.withValues(alpha: 0.3)),
                    child: Row(
                      children: [
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Lapor jalan rusak dalam\nkurang dari 1 menit!', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 18, height: 1.3)),
                              const SizedBox(height: 16),
                              GestureDetector(
                                onTap: _navigateToCreateReport,
                                child: Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                                  decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
                                  child: const Text('Buat Laporan Sekarang', style: TextStyle(color: AppTheme.primary, fontSize: 12, fontWeight: FontWeight.w800)),
                                ),
                              ),
                            ],
                          ),
                        ),
                        Icon(Icons.shield_rounded, size: 72, color: Colors.white.withValues(alpha: 0.9)),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            
            const SizedBox(height: 32),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Laporan Terbaru', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w800, letterSpacing: -0.5, color: AppTheme.neutral900)),
                  GestureDetector(
                    onTap: () => setState(() => _currentIndex = 3),
                    child: const Text('Lihat Semua', style: TextStyle(color: AppTheme.primary, fontWeight: FontWeight.w700, fontSize: 14)),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),
            if (_loading)
              const Center(child: Padding(padding: EdgeInsets.all(32), child: CircularProgressIndicator()))
            else if (_recentReports.isEmpty)
              const Center(
                child: Padding(
                  padding: EdgeInsets.all(32),
                  child: Column(
                    children: [
                      Icon(Icons.inbox_rounded, size: 64, color: AppTheme.neutral300),
                      SizedBox(height: 16),
                      Text('Belum ada laporan', style: TextStyle(color: AppTheme.neutral700, fontWeight: FontWeight.w500, fontSize: 16)),
                    ],
                  ),
                ),
              )
            else
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24),
                child: Column(children: _recentReports.map((r) => _reportCard(r)).toList()),
              ),
          ],
        ),
      ),
    );
  }

  Widget _reportCard(Map<String, dynamic> report) {
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
                        errorWidget: (_, __, ___) => _damageIconBox(report),
                      )
                    : _damageIconBox(report),
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
                            style: TextStyle(fontSize: 11, color: AppTheme.getStatusColor(report['status'] ?? ''), fontWeight: FontWeight.w500),
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
                            style: TextStyle(fontSize: 11, color: AppTheme.getDamageColor(report['damage_level'] ?? ''), fontWeight: FontWeight.w500),
                          ),
                        ),
                      ],
                    ),
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

  /// Fallback damage icon when no photo is available
  Widget _damageIconBox(Map<String, dynamic> report) {
    return Container(
      width: 64,
      height: 64,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(16),
        color: AppTheme.getDamageColor(report['damage_level'] ?? '').withValues(alpha: 0.1),
      ),
      child: Icon(
        Icons.warning_amber_rounded,
        color: AppTheme.getDamageColor(report['damage_level'] ?? ''),
        size: 32,
      ),
    );
  }

  Widget _buildMapTab() {
    return Stack(
      children: [
        GoogleMap(
          initialCameraPosition: const CameraPosition(
            target: LatLng(-7.115, 112.417),
            zoom: 13,
          ),
          onMapCreated: (controller) {
            controller.setMapStyle(_mapStyle);
          },
          zoomControlsEnabled: false,
          myLocationButtonEnabled: false,
          compassEnabled: false,
          mapToolbarEnabled: false,
          markers: _mapReports.map((r) {
            final lat = double.tryParse(r['latitude'].toString()) ?? 0.0;
            final lng = double.tryParse(r['longitude'].toString()) ?? 0.0;
            return Marker(
              markerId: MarkerId(r['id'].toString()),
              position: LatLng(lat, lng),
              infoWindow: InfoWindow(title: r['address'], snippet: r['damage_level']),
              icon: BitmapDescriptor.defaultMarkerWithHue(
                r['damage_level'] == 'berat' ? BitmapDescriptor.hueRed : r['damage_level'] == 'sedang' ? BitmapDescriptor.hueOrange : BitmapDescriptor.hueYellow,
              ),
            );
          }).toSet(),
        ),
        
        // Floating Top Search Bar (Glass)
        Positioned(
          top: 64,
          left: 24,
          right: 24,
          child: GlassContainer(
            borderRadius: BorderRadius.circular(24),
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: Colors.white,
            opacity: 0.85,
            child: Row(
              children: [
                const Icon(Icons.search_rounded, color: AppTheme.neutral300),
                const SizedBox(width: 12),
                const Expanded(child: Text('Cari lokasi laporan...', style: TextStyle(color: AppTheme.neutral300, fontSize: 16, fontWeight: FontWeight.w500))),
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(color: AppTheme.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                  child: const Icon(Icons.tune_rounded, color: AppTheme.primary, size: 20),
                ),
              ],
            ),
          ),
        ),

        // Legend
        Positioned(
          top: 128,
          right: 24,
          child: GlassContainer(
            borderRadius: BorderRadius.circular(16),
            padding: const EdgeInsets.all(12),
            color: Colors.white,
            opacity: 0.9,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                const Text('Tingkat Kerusakan', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w800, color: AppTheme.neutral900)),
                const SizedBox(height: 8),
                _legendItem(Colors.red, 'Berat'),
                const SizedBox(height: 4),
                _legendItem(Colors.orange, 'Sedang'),
                const SizedBox(height: 4),
                _legendItem(Colors.amber, 'Ringan'),
              ],
            ),
          ),
        ),

        // Floating Bottom Map Detail Sheet
        Positioned(
          bottom: 150,
          left: 24,
          right: 24,
          child: GlassContainer(
            borderRadius: BorderRadius.circular(24),
            padding: const EdgeInsets.all(20),
            color: Colors.white,
            opacity: 0.9,
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(color: AppTheme.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(16)),
                  child: const Icon(Icons.map_rounded, color: AppTheme.primary, size: 32),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('${_mapReports.length} Laporan Ditemukan', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16, color: AppTheme.neutral900, letterSpacing: -0.5)),
                      const SizedBox(height: 4),
                      const Text('Jelajahi kerusakan di sekitarmu', style: TextStyle(color: AppTheme.neutral700, fontSize: 13)),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _legendItem(Color color, String label) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(width: 10, height: 10, decoration: BoxDecoration(color: color, shape: BoxShape.circle)),
        const SizedBox(width: 6),
        Text(label, style: const TextStyle(fontSize: 11, color: AppTheme.neutral700, fontWeight: FontWeight.w600)),
      ],
    );
  }

  Widget _buildReportsTab() {
    return Column(
      children: [
        Container(
          width: double.infinity,
          padding: const EdgeInsets.fromLTRB(24, 64, 24, 32),
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
              const Text('Laporan Saya', style: TextStyle(fontSize: 26, fontWeight: FontWeight.w800, color: Colors.white, letterSpacing: -0.5)),
              const SizedBox(height: 8),
              Text('Riwayat interaksi Anda untuk kota tercinta.', style: TextStyle(color: Colors.white.withValues(alpha: 0.8), fontSize: 15)),
            ],
          ),
        ),
        Expanded(
          child: _myLoading
              ? const Center(child: CircularProgressIndicator())
              : _myReports.isEmpty
                  ? const Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.inbox_rounded, size: 64, color: AppTheme.neutral300),
                          SizedBox(height: 16),
                          Text('Belum ada laporan', style: TextStyle(color: AppTheme.neutral700, fontWeight: FontWeight.w500, fontSize: 16)),
                          SizedBox(height: 8),
                          Text('Laporan yang Anda buat akan muncul di sini.', style: TextStyle(color: AppTheme.neutral300, fontSize: 13)),
                        ],
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.fromLTRB(24, 24, 24, 120),
                      itemCount: _myReports.length,
                      itemBuilder: (_, i) => _reportCard(_myReports[i]),
                    ),
        ),
      ],
    );
  }

  Widget _buildProfileTab() {
    return SingleChildScrollView(
      padding: const EdgeInsets.only(bottom: 120),
      child: Column(
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.only(top: 80, bottom: 40),
            decoration: const BoxDecoration(
              gradient: LinearGradient(begin: Alignment.topCenter, end: Alignment.bottomCenter, colors: [AppTheme.primary, AppTheme.primaryDark]),
              borderRadius: BorderRadius.only(bottomLeft: Radius.circular(40), bottomRight: Radius.circular(40)),
            ),
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.all(4),
                  decoration: BoxDecoration(shape: BoxShape.circle, border: Border.all(color: Colors.white.withValues(alpha: 0.3), width: 4)),
                  child: CircleAvatar(
                    radius: 48, 
                    backgroundColor: Colors.white, 
                    child: Text(_userName.isNotEmpty ? _userName[0].toUpperCase() : 'U', style: const TextStyle(fontSize: 40, fontWeight: FontWeight.w800, color: AppTheme.primary)),
                  ),
                ),
                const SizedBox(height: 16),
                Text(_userName, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.w800, color: Colors.white, letterSpacing: -0.5)),
                const SizedBox(height: 8),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
                  decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(20)),
                  child: const Text('Masyarakat Lamongan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 13)),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Pengaturan Akun', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700, color: AppTheme.neutral700)),
                const SizedBox(height: 16),
                _profileTile(Icons.person_outline, 'Edit Profil', () => context.push('/profile')),
                _profileTile(Icons.assignment_outlined, 'Laporan Saya', () => context.push('/my-reports')),
                _profileTile(Icons.notifications_outlined, 'Notifikasi', () => context.push('/notifications')),
                const SizedBox(height: 32),
                const Text('Lainnya', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700, color: AppTheme.neutral700)),
                const SizedBox(height: 16),
                _profileTile(Icons.help_outline, 'Bantuan & FAQ', () {}),
                _profileTile(Icons.logout_rounded, 'Keluar', () async {
                  final prefs = await SharedPreferences.getInstance();
                  await prefs.clear();
                  if (!mounted) return;
                  context.go('/login');
                }, color: AppTheme.danger, isNoShadow: true),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _profileTile(IconData icon, String title, VoidCallback onTap, {Color? color, bool isNoShadow = false}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Ink(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppTheme.neutral300.withValues(alpha: 0.5)),
            boxShadow: isNoShadow ? [] : [
              BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 10, offset: const Offset(0, 4)),
            ],
          ),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(color: (color ?? AppTheme.primary).withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: Icon(icon, color: color ?? AppTheme.primary, size: 24),
              ),
              const SizedBox(width: 16),
              Expanded(child: Text(title, style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600, color: color ?? AppTheme.neutral900))),
              const Icon(Icons.chevron_right_rounded, color: AppTheme.neutral300),
            ],
          ),
        ),
      ),
    );
  }
}

const String _mapStyle = '''
[
  {"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},
  {"elementType":"labels.icon","stylers":[{"visibility":"off"}]},
  {"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},
  {"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},
  {"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},
  {"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},
  {"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},
  {"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},
  {"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},
  {"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},
  {"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},
  {"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},
  {"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},
  {"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},
  {"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}
]
''';
