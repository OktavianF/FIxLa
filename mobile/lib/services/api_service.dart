import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static String get baseUrl {
    if (kIsWeb) {
      return 'http://127.0.0.1:8000/api/v1'; // Flutter Web
    }
    // IP baru terdeteksi: 192.168.43.144
    return 'http://192.168.1.4:8000/api/v1'; 
  }

  late final Dio _dio;

  ApiService() {
    _dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 15),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final prefs = await SharedPreferences.getInstance();
        final token = prefs.getString('auth_token');
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        handler.next(options);
      },
      onError: (error, handler) {
        if (error.response?.statusCode == 401) {
          _clearAuth();
        }
        handler.next(error);
      },
    ));
  }

  Future<void> _clearAuth() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    await prefs.remove('user_data');
  }

  // Auth
  Future<Response> register(Map<String, dynamic> data) =>
      _dio.post('/register', data: data);

  Future<Response> login(String email, String password) =>
      _dio.post('/login', data: {'email': email, 'password': password});

  Future<Response> logout() => _dio.post('/logout');

  Future<Response> getMe() => _dio.get('/me');

  // Reports
  Future<Response> getReports({Map<String, dynamic>? params}) =>
      _dio.get('/reports', queryParameters: params);

  Future<Response> getMapReports({Map<String, dynamic>? bounds}) =>
      _dio.get('/reports/map', queryParameters: bounds != null ? {'bounds': bounds} : null);

  Future<Response> getReport(int id) => _dio.get('/reports/$id');

  Future<Response> createReport(FormData formData) =>
      _dio.post('/reports', data: formData, options: Options(
        headers: {'Content-Type': 'multipart/form-data'},
      ));

  Future<Response> getMyReports({String? status, int page = 1}) =>
      _dio.get('/my-reports', queryParameters: {
        'page': page,
        if (status != null) 'status': status,
      });

  // Notifications
  Future<Response> getNotifications({int page = 1}) =>
      _dio.get('/notifications', queryParameters: {'page': page});

  Future<Response> getUnreadCount() => _dio.get('/notifications/unread-count');

  Future<Response> markAsRead(int id) =>
      _dio.patch('/notifications/$id/read');

  Future<Response> markAllAsRead() => _dio.patch('/notifications/read-all');
}
