import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'theme/app_theme.dart';
import 'screens/splash_screen.dart';
import 'screens/login_screen.dart';
import 'screens/register_screen.dart';
import 'screens/home_screen.dart';
import 'screens/report_form_screen.dart';
import 'screens/report_detail_screen.dart';
import 'screens/my_reports_screen.dart';
import 'screens/profile_screen.dart';
import 'screens/notification_screen.dart';

void main() {
  runApp(const ProviderScope(child: FixLAApp()));
}

final _router = GoRouter(
  initialLocation: '/splash',
  routes: [
    GoRoute(path: '/splash', builder: (_, __) => const SplashScreen()),
    GoRoute(path: '/login', builder: (_, __) => const LoginScreen()),
    GoRoute(path: '/register', builder: (_, __) => const RegisterScreen()),
    GoRoute(path: '/home', builder: (_, __) => const HomeScreen()),
    GoRoute(path: '/report/create', builder: (_, __) => const ReportFormScreen()),
    GoRoute(
      path: '/report/:id',
      builder: (_, state) => ReportDetailScreen(
        reportId: int.parse(state.pathParameters['id']!),
      ),
    ),
    GoRoute(path: '/my-reports', builder: (_, __) => const MyReportsScreen()),
    GoRoute(path: '/profile', builder: (_, __) => const ProfileScreen()),
    GoRoute(path: '/notifications', builder: (_, __) => const NotificationScreen()),
  ],
);

class FixLAApp extends StatelessWidget {
  const FixLAApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      title: 'FixLA - Lapor Jalan Lamongan',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      routerConfig: _router,
    );
  }
}
