import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:geolocator/geolocator.dart';
import 'package:geocoding/geocoding.dart';
import 'package:image_picker/image_picker.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';

class ReportFormScreen extends StatefulWidget {
  const ReportFormScreen({super.key});

  @override
  State<ReportFormScreen> createState() => _ReportFormScreenState();
}

class _ReportFormScreenState extends State<ReportFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _descCtrl = TextEditingController();
  final _addressCtrl = TextEditingController();
  final _districtCtrl = TextEditingController();
  String _damageLevel = 'sedang';

  double? _lat;
  double? _lng;
  final List<XFile> _photos = [];
  bool _loading = false;
  bool _locating = false;

  @override
  void initState() {
    super.initState();
    _getCurrentLocation();
  }

  Future<void> _getCurrentLocation() async {
    setState(() => _locating = true);
    try {
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
      }
      if (permission == LocationPermission.denied || permission == LocationPermission.deniedForever) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Izin lokasi ditolak')),
          );
        }
        setState(() => _locating = false);
        return;
      }

      final pos = await Geolocator.getCurrentPosition(
        locationSettings: const LocationSettings(accuracy: LocationAccuracy.high),
      );
      _lat = pos.latitude;
      _lng = pos.longitude;

      try {
        final placemarks = await placemarkFromCoordinates(pos.latitude, pos.longitude);
        if (placemarks.isNotEmpty) {
          final p = placemarks.first;
          _addressCtrl.text = '${p.street}, ${p.subLocality}, ${p.locality}';
          _districtCtrl.text = p.subLocality ?? '';
        }
      } catch (_) {}
    } catch (_) {}
    if (mounted) setState(() => _locating = false);
  }

  Future<void> _pickPhotos() async {
    final picker = ImagePicker();
    final images = await picker.pickMultiImage(maxWidth: 1280, imageQuality: 75);
    if (images.isNotEmpty) {
      setState(() {
        _photos.addAll(images.take(5 - _photos.length));
      });
    }
  }

  Future<void> _takePhoto() async {
    if (_photos.length >= 5) return;
    final picker = ImagePicker();
    final image = await picker.pickImage(source: ImageSource.camera, maxWidth: 1280, imageQuality: 75);
    if (image != null) {
      setState(() => _photos.add(image));
    }
  }

  Future<void> _submitReport() async {
    if (!_formKey.currentState!.validate()) return;
    if (_photos.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Tambahkan minimal 1 foto')),
      );
      return;
    }
    if (_lat == null || _lng == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Lokasi belum terdeteksi')),
      );
      return;
    }

    setState(() => _loading = true);
    try {
      final formData = FormData.fromMap({
        'latitude': _lat.toString(),
        'longitude': _lng.toString(),
        'address': _addressCtrl.text.trim(),
        'district': _districtCtrl.text.trim(),
        'damage_level': _damageLevel,
        'description': _descCtrl.text.trim(),
      });

      for (var photo in _photos) {
        final bytes = await photo.readAsBytes();
        formData.files.add(MapEntry(
          'photos[]',
          MultipartFile.fromBytes(bytes, filename: photo.name),
        ));
      }

      final api = ApiService();
      await api.createReport(formData);

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Laporan berhasil dikirim! 🎉'), backgroundColor: AppTheme.success),
      );
      context.pop(true);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal mengirim laporan'), backgroundColor: AppTheme.danger),
        );
      }
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.neutral100,
      body: Stack(
        children: [
          // Background Gradient Header
          Container(
            height: 250,
            decoration: const BoxDecoration(
              gradient: LinearGradient(begin: Alignment.topLeft, end: Alignment.bottomRight, colors: [AppTheme.primary, AppTheme.primaryDark]),
              borderRadius: BorderRadius.only(bottomLeft: Radius.circular(40), bottomRight: Radius.circular(40)),
            ),
          ),
          SafeArea(
            bottom: false,
            child: Column(
              children: [
                // Custom AppBar
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  child: Row(
                    children: [
                      IconButton(
                        icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.white),
                        onPressed: () => context.pop(),
                      ),
                      const SizedBox(width: 8),
                      const Text('Buat Laporan', style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w800, letterSpacing: -0.5)),
                    ],
                  ),
                ),
                Expanded(
                  child: Form(
                    key: _formKey,
                    child: SingleChildScrollView(
                      padding: const EdgeInsets.only(left: 20, right: 20, top: 16, bottom: 120),
                      physics: const BouncingScrollPhysics(),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          _buildPhotoCard(),
                          const SizedBox(height: 16),
                          _buildLocationCard(),
                          const SizedBox(height: 16),
                          _buildDamageCard(),
                          const SizedBox(height: 16),
                          _buildDescCard(),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          // Floating Submit Button
          Positioned(
            bottom: 32,
            left: 24,
            right: 24,
            child: Container(
              decoration: BoxDecoration(
                boxShadow: [BoxShadow(color: AppTheme.primary.withValues(alpha: 0.3), blurRadius: 20, offset: const Offset(0, 10))],
                borderRadius: BorderRadius.circular(20),
              ),
              child: ElevatedButton(
                onPressed: _loading ? null : _submitReport,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.primary,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 18),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  elevation: 0,
                ),
                child: _loading 
                    ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 3))
                    : const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.send_rounded, size: 24),
                          SizedBox(width: 12),
                          Text('Kirim Laporan', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
                        ],
                      ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCard({required String title, required IconData icon, required Widget child}) {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 15, offset: const Offset(0, 5))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(color: AppTheme.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
                child: Icon(icon, color: AppTheme.primary, size: 20),
              ),
              const SizedBox(width: 12),
              Text(title, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w700, color: AppTheme.neutral900, letterSpacing: -0.5)),
            ],
          ),
          const SizedBox(height: 20),
          child,
        ],
      ),
    );
  }

  Future<Widget> _buildImagePreview(XFile file) async {
    if (kIsWeb) {
      final bytes = await file.readAsBytes();
      return Image.memory(bytes, width: 110, height: 110, fit: BoxFit.cover);
    }
    return Image.file(File(file.path), width: 110, height: 110, fit: BoxFit.cover);
  }

  Widget _buildPhotoCard() {
    return _buildCard(
      title: 'Foto Bukti *',
      icon: Icons.camera_alt_rounded,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            height: 110,
            child: ListView(
              scrollDirection: Axis.horizontal,
              children: [
                ..._photos.map((p) => Padding(
                  padding: const EdgeInsets.only(right: 12),
                  child: Stack(
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(20),
                        child: FutureBuilder<Widget>(
                          future: _buildImagePreview(p),
                          builder: (context, snapshot) {
                            if (snapshot.hasData) return snapshot.data!;
                            return Container(
                              width: 110, height: 110,
                              decoration: BoxDecoration(color: AppTheme.neutral300, borderRadius: BorderRadius.circular(20)),
                              child: const Center(child: CircularProgressIndicator(strokeWidth: 2)),
                            );
                          },
                        ),
                      ),
                      Positioned(
                        top: 6,
                        right: 6,
                        child: GestureDetector(
                          onTap: () => setState(() => _photos.remove(p)),
                          child: Container(
                            padding: const EdgeInsets.all(4),
                            decoration: BoxDecoration(color: Colors.black.withValues(alpha: 0.5), shape: BoxShape.circle),
                            child: const Icon(Icons.close_rounded, color: Colors.white, size: 18),
                          ),
                        ),
                      ),
                    ],
                  ),
                )),
                if (_photos.length < 5)
                  GestureDetector(
                    onTap: _showPhotoOptions,
                    child: Container(
                      width: 110,
                      height: 110,
                      decoration: BoxDecoration(
                        color: AppTheme.neutral100,
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: AppTheme.neutral300, width: 2),
                      ),
                      child: const Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.add_a_photo_rounded, color: AppTheme.primary, size: 32),
                          SizedBox(height: 8),
                          Text('Tambah', style: TextStyle(fontSize: 13, color: AppTheme.primary, fontWeight: FontWeight.w700)),
                        ],
                      ),
                    ),
                  ),
              ],
            ),
          ),
          const SizedBox(height: 12),
          const Text('Bisa upload hingga 5 foto.', style: TextStyle(fontSize: 13, color: AppTheme.neutral700)),
        ],
      ),
    );
  }

  Widget _buildLocationCard() {
    return _buildCard(
      title: 'Lokasi Jalan',
      icon: Icons.location_on_rounded,
      child: Column(
        children: [
          if (_locating)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              margin: const EdgeInsets.only(bottom: 16),
              decoration: BoxDecoration(color: AppTheme.warning.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(16)),
              child: const Row(children: [SizedBox(width: 16, height: 16, child: CircularProgressIndicator(strokeWidth: 2, color: AppTheme.warning)), SizedBox(width: 12), Text('Mendeteksi GPS otomatis...', style: TextStyle(color: AppTheme.warning, fontWeight: FontWeight.w700))]),
            )
          else if (_lat != null)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              margin: const EdgeInsets.only(bottom: 16),
              decoration: BoxDecoration(color: AppTheme.success.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(16)),
              child: Row(
                children: [
                  const Icon(Icons.check_circle_rounded, color: AppTheme.success, size: 20),
                  const SizedBox(width: 8),
                  Text('GPS: ${_lat!.toStringAsFixed(5)}, ${_lng!.toStringAsFixed(5)}', style: const TextStyle(fontSize: 14, color: AppTheme.success, fontWeight: FontWeight.w700)),
                ],
              ),
            ),
          TextFormField(
            controller: _addressCtrl,
            decoration: const InputDecoration(labelText: 'Alamat Lengkap', prefixIcon: Icon(Icons.map_outlined), filled: true, fillColor: AppTheme.neutral100),
            validator: (v) => (v == null || v.isEmpty) ? 'Masukkan alamat' : null,
          ),
          const SizedBox(height: 16),
          TextFormField(
            controller: _districtCtrl,
            decoration: const InputDecoration(labelText: 'Kecamatan', prefixIcon: Icon(Icons.location_city_rounded), filled: true, fillColor: AppTheme.neutral100),
            validator: (v) => (v == null || v.isEmpty) ? 'Masukkan kecamatan' : null,
          ),
        ],
      ),
    );
  }

  Widget _buildDamageCard() {
    return _buildCard(
      title: 'Tingkat Keparahan *',
      icon: Icons.warning_rounded,
      child: Row(
        children: ['ringan', 'sedang', 'berat'].map((level) {
          final selected = _damageLevel == level;
          return Expanded(
            child: Padding(
              padding: EdgeInsets.only(right: level != 'berat' ? 8 : 0),
              child: GestureDetector(
                onTap: () => setState(() => _damageLevel = level),
                child: AnimatedContainer(
                  duration: const Duration(milliseconds: 200),
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  decoration: BoxDecoration(
                    color: selected ? AppTheme.getDamageColor(level).withValues(alpha: 0.1) : AppTheme.neutral100,
                    border: Border.all(color: selected ? AppTheme.getDamageColor(level) : Colors.transparent, width: 2),
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Column(
                    children: [
                      Icon(
                        level == 'ringan' ? Icons.info_rounded : level == 'sedang' ? Icons.warning_amber_rounded : Icons.dangerous_rounded,
                        color: selected ? AppTheme.getDamageColor(level) : AppTheme.neutral300,
                        size: 32,
                      ),
                      const SizedBox(height: 8),
                      Text(
                        level.toUpperCase(),
                        style: TextStyle(fontSize: 12, fontWeight: selected ? FontWeight.w800 : FontWeight.w600, color: selected ? AppTheme.getDamageColor(level) : AppTheme.neutral700),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildDescCard() {
    return _buildCard(
      title: 'Deskripsi Detail',
      icon: Icons.notes_rounded,
      child: TextFormField(
        controller: _descCtrl,
        maxLines: 4,
        decoration: InputDecoration(
          hintText: 'Misal: Lubang berdiameter 50cm, sangat membahayakan pengendara motor di malam hari...',
          filled: true,
          fillColor: AppTheme.neutral100,
          border: OutlineInputBorder(borderSide: BorderSide.none, borderRadius: BorderRadius.circular(16)),
        ),
      ),
    );
  }

  void _showPhotoOptions() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(32))),
      builder: (_) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Ambil Foto', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: AppTheme.neutral900)),
              const SizedBox(height: 16),
              ListTile(
                contentPadding: EdgeInsets.zero,
                leading: Container(padding: const EdgeInsets.all(12), decoration: BoxDecoration(color: AppTheme.primary.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)), child: const Icon(Icons.camera_alt_rounded, color: AppTheme.primary)), 
                title: const Text('Kamera', style: TextStyle(fontWeight: FontWeight.w600)), 
                onTap: () { Navigator.pop(context); _takePhoto(); }
              ),
              ListTile(
                contentPadding: EdgeInsets.zero,
                leading: Container(padding: const EdgeInsets.all(12), decoration: BoxDecoration(color: AppTheme.accent.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)), child: const Icon(Icons.photo_library_rounded, color: AppTheme.accent)), 
                title: const Text('Galeri', style: TextStyle(fontWeight: FontWeight.w600)), 
                onTap: () { Navigator.pop(context); _pickPhotos(); }
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _descCtrl.dispose();
    _addressCtrl.dispose();
    _districtCtrl.dispose();
    super.dispose();
  }
}
