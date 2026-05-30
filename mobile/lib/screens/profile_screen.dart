import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:image_picker/image_picker.dart';
import 'package:dio/dio.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  String _email = '';
  String? _avatarUrl;
  File? _imageFile;
  bool _loading = false;

  @override
  void initState() {
    super.initState();
    _loadUser();
  }

  Future<void> _loadUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userData = prefs.getString('user_data');
    if (userData != null) {
      final user = jsonDecode(userData);
      setState(() {
        _nameCtrl.text = user['name'] ?? '';
        _phoneCtrl.text = user['phone'] ?? '';
        _email = user['email'] ?? '';
        if (user['avatar'] != null) {
          _avatarUrl = '${ApiService.baseUrl.replaceAll('/api/v1', '')}/storage/${user['avatar']}';
        }
      });
    }
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery, imageQuality: 70);
    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
      });
    }
  }

  Future<void> _updateProfile() async {
    if (!_formKey.currentState!.validate()) return;
    
    setState(() => _loading = true);
    try {
      final formData = FormData.fromMap({
        'name': _nameCtrl.text,
        'phone': _phoneCtrl.text,
      });

      if (_imageFile != null) {
        formData.files.add(MapEntry(
          'avatar',
          await MultipartFile.fromFile(_imageFile!.path, filename: _imageFile!.path.split('/').last),
        ));
      }

      final res = await ApiService().updateProfile(formData);
      
      // Update local storage
      final prefs = await SharedPreferences.getInstance();
      final user = res.data['data'];
      await prefs.setString('user_data', jsonEncode(user));

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Profil berhasil diperbarui'), backgroundColor: AppTheme.success),
      );
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Gagal memperbarui profil'), backgroundColor: AppTheme.danger),
      );
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Edit Profil')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              GestureDetector(
                onTap: _pickImage,
                child: Stack(
                  children: [
                    CircleAvatar(
                      radius: 48,
                      backgroundColor: AppTheme.primary.withOpacity(0.1),
                      backgroundImage: _imageFile != null 
                          ? FileImage(_imageFile!) as ImageProvider
                          : (_avatarUrl != null ? CachedNetworkImageProvider(_avatarUrl!) : null),
                      child: (_imageFile == null && _avatarUrl == null)
                          ? Text(
                              _nameCtrl.text.isNotEmpty ? _nameCtrl.text[0].toUpperCase() : 'U',
                              style: const TextStyle(fontSize: 36, color: AppTheme.primary, fontWeight: FontWeight.w800),
                            )
                          : null,
                    ),
                    Positioned(
                      bottom: 0,
                      right: 0,
                      child: Container(
                        padding: const EdgeInsets.all(6),
                        decoration: BoxDecoration(
                          color: AppTheme.primary,
                          shape: BoxShape.circle,
                          border: Border.all(color: Colors.white, width: 2),
                        ),
                        child: const Icon(Icons.camera_alt, size: 16, color: Colors.white),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 8),
              Text(_email, style: TextStyle(color: Colors.grey[600])),
              const SizedBox(height: 24),
              TextFormField(
                controller: _nameCtrl,
                decoration: const InputDecoration(labelText: 'Nama Lengkap', prefixIcon: Icon(Icons.person_outline)),
                validator: (v) => (v == null || v.isEmpty) ? 'Masukkan nama' : null,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _phoneCtrl,
                decoration: const InputDecoration(labelText: 'No. Telepon', prefixIcon: Icon(Icons.phone_outlined)),
                keyboardType: TextInputType.phone,
              ),
              const SizedBox(height: 32),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _loading ? null : _updateProfile,
                  child: _loading
                      ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                      : const Text('Simpan Perubahan'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _nameCtrl.dispose();
    _phoneCtrl.dispose();
    super.dispose();
  }
}
