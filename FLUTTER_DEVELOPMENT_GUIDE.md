# 📱 Flutter Mobile App - Complete Development Guide

Panduan lengkap untuk mengembangkan, menjalankan, dan mem-build aplikasi Flutter untuk lomba.

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Setup & Installation](#setup--installation)
3. [Project Structure](#project-structure)
4. [Running Locally](#running-locally)
5. [Building APK](#building-apk)
6. [Testing on Emulator](#testing-on-emulator)
7. [Troubleshooting](#troubleshooting)

---

## 🎯 Project Overview

**Aplikasi:** Tugas Akhir - Platform Perlombaan Mobile
**Framework:** Flutter (Dart)
**Backend:** Laravel API (sudah tersedia)
**State Management:** Provider
**Authentication:** JWT Tokens (via Laravel Sanctum)
**Storage:** Flutter Secure Storage (untuk token)

**Fitur Utama:**
- ✅ Register & Login
- ✅ Lihat daftar kompetisi
- ✅ Lihat detail kompetisi
- ✅ Daftar ke kompetisi
- ✅ Lihat pendaftaran saya
- ✅ (Admin) Create/Update/Delete kompetisi
- ✅ (Admin) Lihat semua pendaftaran

---

## 🔧 Setup & Installation

### Prerequisites

Pastikan sudah install:

```bash
# Check Flutter installation
flutter --version

# Output should show:
# Flutter 3.x.x • channel stable
# Dart 3.x.x
```

Jika belum, install dari: https://flutter.dev/docs/get-started/install

### 1. Setup Project

```bash
# Navigate ke mobile app directory
cd /home/kali/tugas_akhir_mobile

# Get all dependencies
flutter pub get

# Generate JSON serialization files (jika ada)
flutter pub run build_runner build
```

### 2. Configure Backend URL

File: `lib/services/api_service.dart`

```dart
class ApiService {
  // Untuk Android Emulator - mengakses host machine
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  
  // Untuk iOS Simulator - mengakses localhost
  // static const String baseUrl = 'http://localhost:8000/api';
  
  // Untuk device fisik - ganti dengan IP address machine
  // static const String baseUrl = 'http://192.168.1.XX:8000/api';
}
```

**Catatan penting:**
- `10.0.2.2` = host machine (dari Android emulator)
- `localhost` = macOS iOS simulator
- `192.168.1.XX` = device fisik (sesuaikan IP)

### 3. Ensure Backend is Running

```bash
# Di terminal terpisah, di folder tugas-akhir
cd /home/kali/tugas-akhir

# Start Laravel development server
php artisan serve

# Output: Laravel development server started on http://127.0.0.1:8000
```

---

## 📁 Project Structure

```
tugas_akhir_mobile/
├── lib/
│   ├── main.dart                 # Entry point aplikasi
│   │
│   ├── config/
│   │   └── service_locator.dart  # Dependency injection (GetIt)
│   │
│   ├── models/                   # Data models
│   │   ├── user.dart
│   │   ├── competition.dart
│   │   ├── registration.dart
│   │   └── index.dart            # Barrel export
│   │
│   ├── services/                 # Business logic
│   │   ├── api_service.dart      # HTTP calls ke backend
│   │   └── secure_storage_service.dart
│   │
│   ├── providers/                # State management (Provider)
│   │   ├── auth_provider.dart
│   │   ├── competition_provider.dart
│   │   └── registration_provider.dart
│   │
│   ├── screens/                  # UI Screens
│   │   ├── auth/
│   │   │   ├── login_screen.dart
│   │   │   └── register_screen.dart
│   │   ├── competitions/
│   │   │   ├── competitions_list_screen.dart
│   │   │   └── competition_detail_screen.dart
│   │   └── registrations/
│   │       └── (future registration screens)
│   │
│   └── widgets/                  # Reusable widgets
│
├── pubspec.yaml                  # Dependencies
├── android/                      # Android config
├── ios/                          # iOS config
└── web/                          # Web config (opsional)
```

---

## 🚀 Running Locally

### Setup Emulator

**Android Emulator:**

```bash
# List available emulators
flutter emulators

# Launch emulator (e.g., Pixel 4)
flutter emulators --launch Pixel_4

# Atau buka Android Studio → Virtual Device Manager → Launch emulator
```

**iOS Simulator (macOS only):**

```bash
# List available simulators
xcrun simctl list devices

# Launch simulator
open -a Simulator
```

### Run App on Emulator

```bash
# Pastikan emulator sudah running, kemudian:
flutter run

# Run dengan verbose logging (untuk debugging)
flutter run -v

# Run dengan hot reload aktif (auto reload saat save)
flutter run
```

**Debug Output:**
```
Device Pixel 4 running Android 11.0 found.
Running with verbose logging enabled
Starting Gradle...
Running Gradle task 'assembleDebug'...
✓ Built build/app/outputs/apk/debug/app-debug.apk
Trying to start package com.example.tugas_akhir_mobile on Android device...
✓ Time snapshot from the VM service: 2026-02-23 15:32:10.123
```

### Hot Reload & Hot Restart

Selama `flutter run` aktif, bisa:

```bash
# Tekan 'r' di terminal untuk hot reload
r  # Reload code tanpa restart app (cepat)

# Tekan 'R' untuk hot restart  
R  # Full restart aplikasi (lebih lambat)

# Tekan 'q' untuk quit
q  # Stop running
```

---

## 🏗️ Building APK

### Development APK (untuk testing)

```bash
# Build debug APK
flutter build apk

# Output: build/app/outputs/apk/debug/app-debug.apk

# Output size: ~50-100 MB (termasuk debug symbols)
# Kemampuan: Hanya untuk testing di emulator/device
```

### Release APK (untuk distribution)

```bash
# Build release APK
flutter build apk --release

# Output: build/app/outputs/apk/release/app-release.apk

# Output size: ~20-30 MB (lebih kecil, optimized)
# Kemampuan: Siap untuk Google Play Store
```

### Split APK (untuk berbagai arsitektur)

```bash
# Build split APK untuk berbagai CPU architectures
flutter build apk --split-per-abi

# Outputs:
# - app-armeabi-v7a-release.apk (32-bit ARM)
# - app-arm64-v8a-release.apk   (64-bit ARM)
# - app-x86_64-release.apk      (Intel x86)

# Ukuran lebih kecil per APK (~15-20 MB each)
```

### Build App Bundle (untuk Google Play)

```bash
# Build Android App Bundle (.aab)
# Ini adalah format yang diminta Google Play Store
flutter build appbundle

# Output: build/app/outputs/bundle/release/app-release.aab

# Ukuran: ~15-20 MB
# Dapat di-upload langsung ke Google Play Console
```

---

## 📲 Android Emulator Setup

### Method 1: Android Studio GUI (Recommended)

```bash
# Buka Android Studio
# Tools → Device Manager → Create device

# Select device:
# - Pixel 4
# - Android 11 (API 30)

# Click "Next" → "Finish"

# Kemudian launch device dari Device Manager
```

### Method 2: Command Line

```bash
# List available images
sdkmanager --list

# Create emulator
avdmanager create avd -n Pixel4 \
  -k "system-images;android-30;google_apis;arm64-v8a" \
  -d "Pixel 4"

# Launch
emulator -avd Pixel4

# Run app (dari terminal lain)
flutter run
```

### Method 3: AVD Manager via Android Studio

```bash
# Buka Android Studio
# More Options (bottom menu) → AVD Manager

# Create Virtual Device...
# → Select Hardware (Pixel 4)
# → Select System Image (Android 11)
# → Verify Configuration
# → Finish
```

---

## 🔄 How to Install APK to Device/Emulator

### Via Flutter CLI

```bash
# Automatic - install & run
flutter run

# Atau just install
flutter install
```

### Via ADB (Android Debug Bridge)

```bash
# Install APK ke emulator/device yang connected
adb install -r build/app/outputs/apk/debug/app-debug.apk

# Flags:
# -r  = Replace package if exists
# -s [device-id] = Specify device
# -g  = Grant permissions automatically

# Example:
adb install -r -g build/app/outputs/apk/release/app-release.apk
```

### Via Device File Transfer

```bash
# Transfer APK ke device
adb push build/app/outputs/apk/release/app-release.apk /sdcard/Download/

# Kemudian buka di device → Files → Download → tap APK → Install
```

---

## 🧪 Testing on Emulator

### Quick Test Workflow

```bash
# Terminal 1: Start backend API
cd /home/kali/tugas-akhir
php artisan serve

# Terminal 2: Launch emulator
flutter emulators --launch Pixel_4

# Terminal 3: Run Flutter app
cd /home/kali/tugas_akhir_mobile
flutter run
```

### Test Checklist

- [ ] Login dengan email: `admin@test.com` password: `password123`
- [ ] Login dengan email: `peserta@test.com` password: `password123`
- [ ] Lihat daftar kompetisi
- [ ] Tap kompetisi untuk lihat detail
- [ ] Daftar ke kompetisi (jika User)
- [ ] Lihat "My Registrations" menu
- [ ] Logout dan login ulang

### Create Test Data

```bash
# Di folder tugas-akhir, jalankan:

# Create admin user
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password123'), 'role' => 'admin'])

# Create peserta user  
>>> User::create(['name' => 'Peserta', 'email' => 'peserta@test.com', 'password' => bcrypt('password123'), 'role' => 'peserta'])

# Create competitions
>>> Competition::create(['title' => 'Coding Challenge', 'description' => 'Lorem ipsum...'])
>>> Competition::create(['title' => 'Design Competition', 'description' => 'Lorem ipsum...'])

>>> exit
```

---

## ⚠️ Troubleshooting

### Problem 1: "Emulator: Could not find emulator installation"

**Solusi:**
```bash
# Ensure Android SDK is in PATH
export PATH="$PATH:$HOME/Library/Android/sdk/platform-tools"

# Or set ANDROID_HOME
export ANDROID_HOME=$HOME/Library/Android/sdk
export PATH="$PATH:$ANDROID_HOME/emulator"
export PATH="$PATH:$ANDROID_HOME/platform-tools"

# Then try again
flutter emulators --launch Pixel_4
```

### Problem 2: "Connection refused" saat login

**Penyebab:** Backend API tidak running atau URL salah

**Solusi:**
```bash
# 1. Pastikan backend running
cd /home/kali/tugas-akhir
php artisan serve

# 2. Check baseUrl di api_service.dart
# Android Emulator: 10.0.2.2
# Device Fisik: IP address machine (cek dengan `ipconfig`)

# 3. Test koneksi
curl http://10.0.2.2:8000/api/competitions
```

### Problem 3: "Permission Denied" untuk secure storage

**Penyebab:** FlutterSecureStorage memerlukan permissions di AndroidManifest.xml

**Solusi:**
Edit `android/app/src/main/AndroidManifest.xml`:

```xml
<manifest ...>
    <!-- Add these permissions -->
    <uses-permission android:name="android.permission.USE_FINGERPRINT" />
    <uses-permission android:name="android.permission.USE_BIOMETRIC" />
    
    <application ...>
        <!-- ... -->
    </application>
</manifest>
```

Kemudian rebuild:
```bash
flutter clean
flutter pub get
flutter run
```

### Problem 4: APK tidak terinstall di emulator

**Solusi:**
```bash
# 1. Uninstall versi lama
adb uninstall com.example.tugas_akhir_mobile

# 2. Clean build
flutter clean

# 3. Rebuild dan install
flutter run --release

# 4. Check ADB connection
adb devices
```

### Problem 5: Hot reload tidak bekerja

**Solusi:**
```bash
# 1. Tekan 'R' untuk full restart
R

# 2. Atau stop dan jalankan lagi
q
flutter run
```

---

## 📝 API Integration Endpoints

Aplikasi menggunakan 12 endpoint dari backend Laravel:

### Authentication
```
POST   /api/register          → Register user
POST   /api/login             → Login (returns token)
POST   /api/logout            → Logout
GET    /api/profile           → Get user profile
```

### Competitions
```
GET    /api/competitions      → List all competitions
GET    /api/competitions/{id} → Get competition detail
POST   /api/competitions      → Create (admin only)
PUT    /api/competitions/{id} → Update (admin only)
DELETE /api/competitions/{id} → Delete (admin only)
```

### Registrations
```
GET    /api/registrations     → My registrations
POST   /api/registrations     → Register to competition
GET    /api/registrations/{id} → Get registration detail
DELETE /api/registrations/{id} → Cancel registration
GET    /api/admin/registrations → All registrations (admin only)
```

---

## 🔐 Security Best Practices

```dart
// ✅ Token disimpan di Secure Storage
await secureStorageService.saveToken(token);

// ✅ Token dihapus saat logout
await secureStorageService.deleteToken();

// ✅ Token di-attach ke setiap request yang memerlukan auth
headers['Authorization'] = 'Bearer $token';

// ✅ Handle token expiry
if (response.statusCode == 401) {
  // Token expired, logout user
  authProvider.logout();
}
```

---

## 📦 Release Checklist

Before submitting to Google Play Store:

- [ ] Update `pubspec.yaml` version (1.0.0+1 → next version)
- [ ] Change `baseUrl` ke production URL
- [ ] Run `flutter clean && flutter pub get`
- [ ] Test di emulator & device fisik
- [ ] Build release APK: `flutter build apk --release`
- [ ] Build App Bundle: `flutter build appbundle`
- [ ] Sign APK/Bundle dengan release keystore
- [ ] Upload ke Google Play Console

---

## 🎓 Quick Reference Commands

```bash
# Setup & Development
flutter pub get              # Get dependencies
flutter pub upgrade         # Update dependencies
flutter pub run build_runner build  # Generate files

# Running
flutter run                 # Run on connected device/emulator
flutter run -v              # Verbose logging
flutter run --release       # Run release build

# Building
flutter build apk           # Build debug APK
flutter build apk --release # Build release APK
flutter build appbundle     # Build App Bundle for Play Store

# Debugging
flutter logs                # Show device logs
flutter devices             # List connected devices
flutter clean               # Clean build artifacts
flutter emulators           # List available emulators

# Testing
flutter test                # Run unit tests
flutter test --coverage     # Test with coverage report
```

---

## 📚 Additional Resources

- Flutter Docs: https://flutter.dev/docs
- Dart Docs: https://dart.dev/guides
- Provider Package: https://pub.dev/packages/provider
- Flutter HTTP: https://pub.dev/packages/http
- Security Storage: https://pub.dev/packages/flutter_secure_storage

---

## 💡 Tips & Tricks

**1. Speed up development:**
```bash
# Use hot reload (tekan 'r')
# Tekan 'R' untuk full restart jika diperlukan
```

**2. Debug emulator:**
```bash
# Lihat log real-time
flutter logs

# Filter logs
flutter logs --grep ""
```

**3. Emulator internet access:**
```bash
# Check if emulator can reach host machine
ping 10.0.2.2

# Or test specific port
curl http://10.0.2.2:8000
```

**4. Device storage inspection:**
```bash
adb shell
cd /sdcard/
ls -la
```

---

**Last Updated:** February 23, 2026
**Version:** 1.0.0
**Author:** AI Code Assistant

