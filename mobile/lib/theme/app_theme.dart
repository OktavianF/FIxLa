import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  // New Elegant Color Palette (Indigo & Rose)
  static const Color primary = Color(0xFF4F46E5); // Deep Indigo
  static const Color primaryDark = Color(0xFF312E81); // Midnight Indigo
  static const Color accent = Color(0xFFE11D48); // Vibrant Rose
  static const Color danger = Color(0xFFE11D48); // Rose matches danger
  static const Color warning = Color(0xFFF59E0B); // Amber
  static const Color success = Color(0xFF10B981); // Emerald
  static const Color neutral100 = Color(0xFFF8FAFC); // Slate 50
  static const Color neutral300 = Color(0xFFCBD5E1); // Slate 300
  static const Color neutral700 = Color(0xFF334155); // Slate 700
  static const Color neutral900 = Color(0xFF0F172A); // Slate 900

  // Status colors
  static const Color statusSubmitted = Color(0xFF64748B); // Slate 500
  static const Color statusVerified = Color(0xFF4F46E5); // Indigo
  static const Color statusScheduled = Color(0xFFF59E0B); // Amber
  static const Color statusUnderRepair = Color(0xFF8B5CF6); // Violet
  static const Color statusCompleted = Color(0xFF10B981); // Emerald

  // Damage colors
  static const Color damageRingan = Color(0xFF10B981); // Emerald
  static const Color damageSedang = Color(0xFFF59E0B); // Amber
  static const Color damageBerat = Color(0xFFE11D48); // Rose

  static Color getStatusColor(String status) {
    switch (status) {
      case 'submitted': return statusSubmitted;
      case 'verified': return statusVerified;
      case 'scheduled': return statusScheduled;
      case 'under_repair': return statusUnderRepair;
      case 'completed': return statusCompleted;
      default: return statusSubmitted;
    }
  }

  static IconData getStatusIcon(String status) {
    switch (status) {
      case 'submitted': return Icons.hourglass_empty_rounded;
      case 'verified': return Icons.verified_rounded;
      case 'scheduled': return Icons.event_rounded;
      case 'under_repair': return Icons.build_circle_rounded;
      case 'completed': return Icons.check_circle_rounded;
      default: return Icons.hourglass_empty_rounded;
    }
  }

  static String getStatusLabel(String status) {
    switch (status) {
      case 'submitted': return 'Submitted';
      case 'verified': return 'Verified';
      case 'scheduled': return 'Scheduled';
      case 'under_repair': return 'Under Repair';
      case 'completed': return 'Completed';
      default: return status;
    }
  }

  static Color getDamageColor(String level) {
    switch (level) {
      case 'ringan': return damageRingan;
      case 'sedang': return damageSedang;
      case 'berat': return damageBerat;
      default: return damageSedang;
    }
  }

  static ThemeData get lightTheme {
    final baseTextTheme = GoogleFonts.outfitTextTheme();
    return ThemeData(
      useMaterial3: true,
      colorSchemeSeed: primary,
      textTheme: baseTextTheme,
      scaffoldBackgroundColor: neutral100,
      appBarTheme: AppBarTheme(
        backgroundColor: Colors.white,
        foregroundColor: neutral900,
        elevation: 0,
        scrolledUnderElevation: 0,
        centerTitle: true,
        iconTheme: const IconThemeData(color: neutral900),
        titleTextStyle: GoogleFonts.outfit(
          fontSize: 18,
          fontWeight: FontWeight.w700,
          color: neutral900,
          letterSpacing: -0.5,
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: primary,
          foregroundColor: Colors.white,
          elevation: 0,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          textStyle: GoogleFonts.outfit(
            fontSize: 16,
            fontWeight: FontWeight.w700,
            letterSpacing: 0.2,
          ),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: primary,
          textStyle: GoogleFonts.outfit(
            fontSize: 14,
            fontWeight: FontWeight.w700,
          ),
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: Colors.white,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: neutral300),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: neutral300),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: primary, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: danger, width: 1.5),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
        labelStyle: TextStyle(color: neutral700),
        hintStyle: TextStyle(color: neutral300),
      ),
      cardTheme: CardThemeData(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
          side: BorderSide(color: neutral300.withValues(alpha: 0.5), width: 1),
        ),
        elevation: 0,
        color: Colors.white,
        margin: EdgeInsets.zero,
      ),
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: Colors.white,
        selectedItemColor: primary,
        unselectedItemColor: neutral300,
        type: BottomNavigationBarType.fixed,
        elevation: 0,
        selectedLabelStyle: TextStyle(fontWeight: FontWeight.w700, fontSize: 12),
        unselectedLabelStyle: TextStyle(fontWeight: FontWeight.w500, fontSize: 12),
      ),
      snackBarTheme: SnackBarThemeData(
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }
}

