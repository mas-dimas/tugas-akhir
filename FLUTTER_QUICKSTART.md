# ⚡ Flutter Mobile App - Quick Start (5 Minutes)

Mulai dan jalankan aplikasi Flutter dalam **5 menit**.

---

## 🚀 Quick Start: 5 Steps

### Step 1: Prepare Backend (1 min)

```bash
cd /home/kali/tugas-akhir
php artisan serve

# Output: Server running on http://127.0.0.1:8000
# Keep this terminal open!
```

### Step 2: GetDependencies (1 min)

```bash
cd /home/kali/tugas_akhir_mobile
flutter pub get

# Output: ... packages installed
```

### Step 3: Launch Emulator (1 min)

```bash
# Android Emulator
flutter emulators --launch Pixel_4

# Or open Android Studio → Device Manager → Launch
```

### Step 4: Run App (1 min)

```bash
flutter run

# Output:
# Launching lib/main.dart on Pixel 4 in debug mode...
# ✓ Built build/app/outputs/apk/debug/app-debug.apk (...)
# Installing build/app/outputs/apk/debug/app-debug.apk...
# 
# Flutter run key commands.
# r Hot reload. 🔥🔥🔥
# R Hot restart.
# q Quit (terminate the app from Flutter).
```

### Step 5: Test Login (1 min)

**Credentials:**
```
Email: admin@test.com
Password: password123
```

Atau:
```
Email: peserta@test.com  
Password: password123
```

---

## What You See

| Screen | What It Shows |
|--------|---------------|
| **Login** | Just opened app |
| **Competitions List** | After successful login |
| **Competition Detail** | Tap any competition card |
| **My Registrations** | Menu option |

---

## Hot Reload Magic 🔥

```bash
# While app is running, tekan 'r' untuk instant reload
r  # Changes appear in ~1 second!

# Tekan 'R' untuk full restart
R

# Tekan 'q' untuk quit
q
```

This is the superpower of Flutter - develop 10x faster!

---

## Build Production APK

```bash
# Release build (25MB, optimized)
flutter build apk --release

# Result: build/app/outputs/apk/release/app-release.apk
```

---

## Troubleshooting

### Can't connect to backend?

```bash
# Check api_service.dart baseUrl
# Should be: 10.0.2.2:8000 (for emulator)

# Test manually:
curl http://10.0.2.2:8000/api/competitions
```

### Emulator not starting?

```bash
# List emulators
flutter emulators

# Launch manually
emulator -avd Pixel_4

# Or use Android Studio GUI
```

### APK not installing?

```bash
# Uninstall first
adb uninstall com.example.tugas_akhir_mobile

# Then retry
flutter run
```

---

## What's Next?

1. **Modify UI** → Edit files in `lib/screens/`
2. **Add features** → Create new screens & providers
3. **Build APK** → Follow APK_BUILD_GUIDE.md
4. **Upload Play Store** → Follow distribution guide

---

## Helpful Commands

```bash
flutter run              # Run app
flutter clean           # Clean build
flutter pub get         # Get dependencies
flutter build apk       # Build APK
adb devices            # List connected devices
adb logcat             # View device logs
```

---

**That's it! Happy coding! 🎉**

