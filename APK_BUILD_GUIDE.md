# 📦 APK Building & Installation - Complete Tutorial

Panduan terperinci tentang cara membangun APK dan menginstalnya ke device.

---

## ❓ APK = Apa?

**APK** = Android Package Kit

Seperti `.exe` di Windows atau `.app` di macOS. File yang bisa diinstall di Android devices/emulators.

**Ukuran:** 20-100 MB tergantung build type
**Format:** ZIP archive berisi code + resources + config

---

## 🔄 APK Building Workflow

```
┌─────────────────────────────────┐
│  1. flutter build apk           │ ← Compile code
│                                 │
│  2. Gradle assembles APK        │ ← Build menggunakan Gradle
│                                 │
│  3. Output: app-debug.apk       │ ← File tersedia
│                                 │
│  4. Install via ADB             │ ← Kirim ke device
│                                 │
│  5. App ready di device         │ ← Bisa dijalankan
└─────────────────────────────────┘
```

---

## 🛠️ Build APK Step-by-Step

### Step 1: Prepare Project

```bash
# Navigate ke folder Flutter project
cd /home/kali/tugas_akhir_mobile

# Clean previous builds
flutter clean

# Get latest dependencies
flutter pub get
```

### Step 2: Build APK

**Option A: Debug APK** (untuk testing lokal)

```bash
flutter build apk

# Output: build/app/outputs/apk/debug/app-debug.apk
# Size: ~80 MB
# Speed: Fast
# Optimization: None
# Usage: Testing di emulator/device
```

**Option B: Release APK** (untuk Google Play)

```bash
flutter build apk --release

# Output: build/app/outputs/apk/release/app-release.apk
# Size: ~25 MB
# Speed: Slower (optimization process)
# Optimization: Full (minification, obfuscation)
# Usage: Production / Google Play Store
```

**Option C: Split APK** (terpisah per architecture)

```bash
flutter build apk --split-per-abi --release

# Outputs:
# 1. app-armeabi-v7a-release.apk (32-bit)  ~15 MB
# 2. app-arm64-v8a-release.apk   (64-bit)  ~18 MB
# 3. app-x86_64-release.apk      (Intel)   ~20 MB

# Usage: Lebih optimal, users download sesuai device
```

### Step 3: Verify Build

```bash
# Check file exists dan ukurannya
ls -lh build/app/outputs/apk/debug/app-debug.apk
# Contoh: -rw-r--r-- 1 user group 82M Feb 23 15:00 app-debug.apk

# Atau
ls -lh build/app/outputs/apk/release/app-release.apk
# Contoh: -rw-r--r-- 1 user group 25M Feb 23 15:05 app-release.apk
```

---

## 📥 Installing APK to Device/Emulator

### Method 1: Via Flutter CLI (Easiest)

```bash
# Automatic: build & install & run
flutter run

# Hanya install (tidak run)
flutter install
```

### Method 2: Via ADB Command

**Setup:**
```bash
# Pastikan emulator sudah berjalan atau device terhubung
adb devices

# Output:
# List of attached devices
# emulator-5554          device
# atau
# FA87K0305551            device
```

**Install Debug APK:**
```bash
cd /home/kali/tugas_akhir_mobile

# Install
adb install build/app/outputs/apk/debug/app-debug.apk

# Install dengan replace (jika versi lama masih ada)
adb install -r build/app/outputs/apk/debug/app-debug.apk

# Install dengan grant permissions
adb install -r -g build/app/outputs/apk/debug/app-debug.apk
```

**Install Release APK:**
```bash
adb install -r build/app/outputs/apk/release/app-release.apk
```

**Output Success:**
```
Success
pkg: /data/local/tmp/app-debug.apk
```

**Output Error:**
```bash
# Package installed for different user
# → Uninstall dulu lalu install ulang
adb uninstall com.example.tugas_akhir_mobile
adb install build/app/outputs/apk/debug/app-debug.apk

# Insufficient space
# → Delete app dari device
adb uninstall com.example.tugas_akhir_mobile
```

### Method 3: Manual Install via File Manager

**iOS / Android Device Fisik:**

1. Transfer APK ke device (via USB cable)
   ```bash
   adb push build/app/outputs/apk/release/app-release.apk /sdcard/Download/
   ```

2. Di device:
   - Buka File Manager
   - Navigate ke Downloads folder
   - Tap `app-release.apk`
   - Tap "Install"
   - Tap "Open" jika sudah installed

### Method 4: Drag & Drop (Android Studio)

Android Studio memiliki built-in APK installer:

```bash
# Buka Android Studio
# Click "Profile or deploy APK"atau 
# File → Profile or deploy APK

# Select APK file:
# build/app/outputs/apk/debug/app-debug.apk

# Pilih emulator/device → Install
```

---

## 🔍 Check Installation

### Verify App Installed

```bash
# List installed packages
adb shell pm list packages | grep tugas_akhir

# Output: package:com.example.tugas_akhir_mobile

# Get app info
adb shell dumpsys package com.example.tugas_akhir_mobile
```

### Launch App

```bash
# Via ADB
adb shell am start -n com.example.tugas_akhir_mobile/.MainActivity

# Via Flutter
flutter run

# Atau ketap icon di emulator/device
```

### View Logs While Running

```bash
# Real-time logs
adb logcat

# Filter by app
adb logcat | grep tugas_akhir

# Filter by Flutter
adb logcat | grep flutter

# Clear logs
adb logcat -c
```

---

## 📋 Uninstall APK

```bash
# Via ADB
adb uninstall com.example.tugas_akhir_mobile

# Success output:
# Success

# Get package name untuk uninstall
adb shell pm list packages | grep -i tugas

# Manual uninstall (di device):
# Settings → Apps → Tugas Akhir → Uninstall
```

---

## 🚀 APK Signing for Google Play

Sebelum upload ke Google Play Store, APK harus di-sign dengan release keystore.

### Create Keystore (First Time Only)

```bash
# Generate key
keytool -genkey -v -keystore ~/upload-keystore.jks \
  -keyalg RSA -keysize 2048 -validity 10950 \
  -alias upload

# Prompts:
# Keystore password: [enter password]
# First and last name: [enter name]
# Organization Unit: [enter ou]
# Organization: [enter org]
# City: [enter city]
# State: [enter state]
# Country: [enter country code]
# Is correct? [yes]
```

### Sign APK/Bundle

```bash
# Create build configuration file
# cat > android/key.properties << EOF
storePassword=[password]
keyPassword=[password]
keyAlias=upload
storeFile=/path/to/upload-keystore.jks
EOF

# Build signed release APK
flutter build apk --release

# Build signed App Bundle
flutter build appbundle --release

# Output:
# build/app/outputs/apk/release/app-release.apk (signed)
# build/app/outputs/bundle/release/app-release.aab (signed)
```

---

## 🎯 Build Options Summary

| Option | Speed | Size | Use Case |
|--------|-------|------|----------|
| `flutter build apk` | Fast | 80 MB | Local testing |
| `flutter build apk --release` | Slow | 25 MB | Production |
| `flutter build apk --split-per-abi` | Medium | 15-20 MB each | Optimized distribution |
| `flutter build appbundle` | Medium | 15 MB | Google Play Store |

---

## 🎁 Distribution Channels

### 1. Direct APK Distribution (not recommended)

```bash
# Good for: Internal testing
# Bad for: Updates, security, user experience

# Build & share APK file
# Users install manually
```

### 2. Google Play Store (Recommended)

```bash
# Good for: Updates, security, discovery
# Requirements: Signed App Bundle (.aab)

# Build signed bundle
flutter build appbundle --release

# Upload to Google Play Console
# → Create developer account ($25 one-time)
# → Create app
# → Upload .aab file
# → Set pricing & availability
# → Submit for review
```

### 3. Firebase App Distribution (Beta Testing)

```bash
# Good for: Beta testing
# Requirements: Firebase project

# Build release APK
flutter build apk --release

# Upload via Firebase Console or CLI
firebase appdistribution:distribute build/app/outputs/apk/release/app-release.apk \
  --app 1:123456789:android:abcdef123456
```

### 4. GitHub Releases (For Open Source)

```bash
# Push to GitHub + create release
git add .
git commit -m "Release v1.0.0"
git tag v1.0.0
git push origin main --tags

# Upload APK ke GitHub Releases via web interface
# Atau via github CLI:
gh release create v1.0.0 build/app/outputs/apk/release/app-release.apk
```

---

## 📊 Build Process Detailed Breakdown

### Debug Build

```
Time: ~30-60 seconds
1. Compile Dart → Kernel
2. Compile Kernel → Machine code
3. Run Gradle assembleDebug
4. Package APK
5. Install via ADB
```

### Release Build

```
Time: ~2-3 minutes
1. Compile Dart → Kernel
2. Compile Kernel → Machine code
3. Run Gradle assembleRelease
4. Minify code (remove unused code)
5. Obfuscate (rename variables)
6. Package APK
7. Sign APK
```

---

## ⚙️ Configuration Files

### pubspec.yaml
```yaml
name: tugas_akhir_mobile
version: 1.0.0+1
# 1.0.0 = semantic version
# +1 = build number
```

### android/app/build.gradle

```gradle
android {
    defaultConfig {
        applicationId "com.example.tugas_akhir_mobile"
        minSdkVersion 21         // Min Android 5.0
        targetSdkVersion 34      // Target Android 14
        versionCode 1            // Build number
        versionName "1.0.0"      // Display version
    }
}
```

### android/app/src/main/AndroidManifest.xml

```xml
<manifest package="com.example.tugas_akhir_mobile">
    <application
        android:label="Tugas Akhir"
        android:icon="@mipmap/ic_launcher"
        android:requestLegacyExternalStorage="true"
    >
        <activity
            android:name=".MainActivity"
            android:launchMode="singleTop"
            android:theme="@style/LaunchTheme"
        />
    </application>
    
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.USE_FINGERPRINT" />
</manifest>
```

---

## 🔐 APK Security

### Check APK Contents

```bash
# List files inside APK
unzip -l build/app/outputs/apk/release/app-release.apk | head -20

# Extract APK
unzip build/app/outputs/apk/release/app-release.apk -d apk-extract

# View AndroidManifest.xml
cd apk-extract
cat AndroidManifest.xml  # (will be binary, use tool to decode)
```

### Verify Signing Certificate

```bash
# Check APK signature
jarsigner -verify -certs -verbose build/app/outputs/apk/release/app-release.apk

# Extract certificate
keytool -printcert -jarfile build/app/outputs/apk/release/app-release.apk
```

---

## 🐛 Common Build Errors & Fixes

| Error | Cause | Fix |
|-------|-------|-----|
| `GradleException` | Gradle config error | `flutter clean && flutter pub get` |
| `INSTALL_FAILED_VERSION_DOWNGRADE` | Newer version exists | `adb uninstall ...` |
| `INSTALL_FAILED_INSUFFICIENT_STORAGE` | Device space low | Delete other apps |
| `Entry from file ... not found` | Permission issue | `adb uninstall ...` |
| `64K method limit exceeded` | Too many methods (old) | Update dependencies |

---

## 📝 Quick Checklist: Build & Deploy

```bash
# 1. Check backend running
cd /home/kali/tugas-akhir && php artisan serve

# 2. Update version in pubspec.yaml if needed
# version: 1.0.0+1 → 1.0.1+2

# 3. Clean & prepare
cd /home/kali/tugas_akhir_mobile
flutter clean
flutter pub get

# 4. Test lokally first
flutter run

# 5. Build APK for release
flutter build apk --release

# 6. Verify output
ls -lh build/app/outputs/apk/release/app-release.apk

# 7. Install to device
adb install -r build/app/outputs/apk/release/app-release.apk

# 8. Test di device
# → Open app → Login → Test features

# 9. Ready for distribution!
# → Share APK atau upload ke Play Store
```

---

## 📚 References

- Flutter Build Docs: https://flutter.dev/docs/deployment/android
- Android Developer: https://developer.android.com/studio/build/building-cmdline
- Google Play Console: https://play.google.com
- APK Format: https://developer.android.com/guide/topics/packages/apk-structure

---

**Last Updated:** February 23, 2026
**Version:** 1.0.0

