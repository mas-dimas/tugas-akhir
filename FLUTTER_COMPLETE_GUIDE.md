# 📱 FLUTTER MOBILE APP - COMPLETE PROJECT SUMMARY

Complete overview of the Flutter mobile app project berikut tutorial lengkap penggunaan APK.

---

## 📚 Documentation Structure

Berikut file-file dokumentasi yang tersedia:

### 1. **FLUTTER_QUICKSTART.md** ⚡
- **Waktu:** 5 menit
- **Isi:** Mulai & jalankan app
- **Untuk:** Orang yang ingin cepat mencoba
- **Command:** `flutter run`

### 2. **FLUTTER_DEVELOPMENT_GUIDE.md** 📖
- **Panjang:** 1500+ lines
- **Isi:** Panduan lengkap development
- **Untuk:** Developer yang ingin memahami semuanya
- **Topics:** Setup, structure, running, emulator, troubleshooting

### 3. **APK_BUILD_GUIDE.md** 📦
- **Panjang:** 1200+ lines
- **Isi:** APK building & installation
- **Untuk:** Pertanyaan "bagaimana cara bikin APK?"
- **Topics:** Build process, installation methods, signing, distribution

---

## 🏗️ Project Architecture

```
tugas_akhir_mobile/
├── lib/
│   ├── main.dart                          # Entry point
│   │
│   ├── config/
│   │   └── service_locator.dart           # Dependency injection
│   │
│   ├── models/                            # Data structures
│   │   ├── user.dart
│   │   ├── competition.dart
│   │   ├── registration.dart
│   │   └── index.dart
│   │
│   ├── services/                          # Business logic
│   │   ├── api_service.dart               # API calls (12 endpoints)
│   │   └── secure_storage_service.dart    # Token storage
│   │
│   ├── providers/                         # State management (Provider)
│   │   ├── auth_provider.dart
│   │   ├── competition_provider.dart
│   │   └── registration_provider.dart
│   │
│   ├── screens/                           # UI Screens
│   │   ├── auth/
│   │   │   ├── login_screen.dart
│   │   │   └── register_screen.dart
│   │   ├── competitions/
│   │   │   ├── competitions_list_screen.dart (HOME SCREEN)
│   │   │   └── competition_detail_screen.dart
│   │   └── registrations/
│   │
│   └── widgets/                           # Reusable components
│
├── pubspec.yaml                            # Dependencies
└── [build outputs after flutter build]
```

---

## 🔄 Data Flow Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        UI LAYER                             │
│  LoginScreen → CompetitionsListScreen → DetailScreen        │
│ (7 screens total)                                           │
└────────────────────┬────────────────────────────────────────┘
                     │ (notifyListeners)
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                 STATE MANAGEMENT (Provider)                 │
│  AuthProvider → CompetitionProvider → RegistrationProvider  │
│  (Listen to changes, trigger rebuilds)                      │
└────────────────────┬────────────────────────────────────────┘
                     │ (calls methods)
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                    SERVICES LAYER                           │
│  ┌──────────────────┬─────────────────────────────────────┐ │
│  │ ApiService       │ SecureStorageService                │ │
│  ├──────────────────┼─────────────────────────────────────┤ │
│  │ - register()     │ - saveToken(token)                  │ │
│  │ - login()        │ - getToken()                        │ │
│  │ - logout()       │ - deleteToken()                     │ │
│  │ - getProfile()   │ - clearAll()                        │ │
│  │ - getCompetitions()                                    │ │
│  │ - registerCompetition()                                │ │
│  │ ... (12 endpoints total)                              │ │
│  └──────────────────┴─────────────────────────────────────┘ │
└────────────────────┬────────────────────────────────────────┘
                     │ (HTTP requests)
                     ▼
┌─────────────────────────────────────────────────────────────┐
│               BACKEND LAYER (Laravel API)                   │
│  http://10.0.2.2:8000/api                                  │
│  → 12 REST endpoints                                        │
│  → JWT Token authentication                                │
│  → 3 controllers (Auth, Competition, Registration)          │
│  → 3 models + 3 tables + relationships                      │
└─────────────────────────────────────────────────────────────┘
```

---

## 🚀 Complete Workflow

### 1️⃣ **Development Phase**

```bash
# Terminal 1: Start backend
cd /home/kali/tugas-akhir
php artisan serve

# Terminal 2: Launch emulator
flutter emulators --launch Pixel_4

# Terminal 3: Run app with hot reload
cd /home/kali/tugas_akhir_mobile
flutter run
```

**Features during development:**
- Hot reload: Tekan `r` untuk instant reload
- Hot restart: Tekan `R` untuk full restart
- Hot exit: Tekan `q` untuk quit

### 2️⃣ **Testing Phase**

```bash
# Test login dengan credentials
Email: admin@test.com
Password: password123

# Or
Email: peserta@test.com
Password: password123
```

**Test Checklist:**
- [ ] Register akun baru
- [ ] Login
- [ ] View competitions list
- [ ] View competition detail
- [ ] Register to competition
- [ ] View my registrations
- [ ] Logout & login ulang
- [ ] Admin: Create/update/delete competition

### 3️⃣ **Build Phase**

```bash
# Debug APK (untuk testing)
flutter build apk
# Output: build/app/outputs/apk/debug/app-debug.apk (80 MB)

# Release APK (untuk production)
flutter build apk --release
# Output: build/app/outputs/apk/release/app-release.apk (25 MB)
```

### 4️⃣ **Distribution Phase**

```bash
# Install to device/emulator
adb install -r build/app/outputs/apk/release/app-release.apk

# Or upload ke Google Play Store
flutter build appbundle --release
# Upload ke Google Play Console
```

---

## 🎯 API Endpoints Integration

App mengintegrasikan 12 endpoints dari Laravel backend:

### Authentication (4 endpoints)
```
POST   /api/register          → AuthProvider.register()
POST   /api/login             → AuthProvider.login()
POST   /api/logout            → AuthProvider.logout()
GET    /api/profile           → AuthProvider.getProfile()
```

### Competitions (5 endpoints)
```
GET    /api/competitions      → CompetitionProvider.loadCompetitions()
GET    /api/competitions/{id} → CompetitionProvider.getDetail()
POST   /api/competitions      → CompetitionProvider.create() [ADMIN]
PUT    /api/competitions/{id} → CompetitionProvider.update() [ADMIN]
DELETE /api/competitions/{id} → CompetitionProvider.delete() [ADMIN]
```

### Registrations (4 endpoints)
```
GET    /api/registrations     → RegistrationProvider.loadMyRegistrations()
POST   /api/registrations     → RegistrationProvider.register()
DELETE /api/registrations/{id} → RegistrationProvider.cancel()
GET    /api/admin/registrations → RegistrationProvider.loadAll() [ADMIN]
```

---

## 💾 **APK: Konsep & Flow**

### Apa itu APK?

**APK** = Android Package Kit (seperti .exe di Windows)

```
Source Code (Dart) 
    ↓
Compile to Machine Code
    ↓
Package dengan Resources
    ↓
Gradle Assembly
    ↓
APK File (20-100 MB)
    ↓
Install ke Device/Emulator
    ↓
App Running
```

### Jenis APK

| Type | Size | Speed | Optimization | Use Case |
|------|------|-------|--------------|----------|
| **Debug** | 80 MB | ⚡ Fast | None | Testing lokal |
| **Release** | 25 MB | 🐢 Slow | Full | Production |
| **Split** | 15-20 MB each | ⚙️ Medium | Per-arch | Optimal distribution |

### Build to APK: 3 Langkah

```bash
# Step 1: Prepare
flutter clean && flutter pub get

# Step 2: Build
flutter build apk --release

# Step 3: Install
adb install -r build/app/outputs/apk/release/app-release.apk
```

**Hasil:** Aplikasi terinstall & siap digunakan!

---

## 🔒 Security Implementation

### 1. **Secure Token Storage**

```dart
// Save token securely
await secureStorageService.saveToken(token);

// Retrieve untuk HTTP requests
String? token = await secureStorageService.getToken();

// Clear saat logout
await secureStorageService.deleteToken();
```

**Storage Location:**
- Android: Keystore (encrypted)
- iOS: Keychain (encrypted)

### 2. **JWT Authentication**

```dart
// Attach token ke setiap authenticated request
headers['Authorization'] = 'Bearer $token';

// Backend validates token via Laravel Sanctum
// Response 401 = token expired → logout user
```

### 3. **SSL/TLS for Production**

```dart
// Development
baseUrl = 'http://10.0.2.2:8000/api'  // Allow HTTP

// Production
baseUrl = 'https://api.perlombaan.com/api'  // HTTPS only
```

---

## 📋 **Troubleshooting Quick Guide**

### Problem 1: App tidak bisa connect ke backend

**Penyebab:** Wrong baseUrl atau backend tidak running

**Solusi:**
```bash
# Check backend running
cd /home/kali/tugas-akhir && php artisan serve

# Check api_service.dart baseUrl
# Android Emulator: 10.0.2.2:8000
# Device Fisik: [YOUR_IP]:8000

# Test manually
curl http://10.0.2.2:8000/api/competitions
```

### Problem 2: "Could not find emulator"

**Solusi:**
```bash
flutter emulators
flutter emulators --launch Pixel_4

# Or via Android Studio GUI
```

### Problem 3: APK install failed

**Solusi:**
```bash
adb uninstall com.example.tugas_akhir_mobile
flutter run --release
```

### Problem 4: Hot reload tidak bekerja

**Solusi:**
```bash
# Full restart
R

# Atau quit & run ulang
q
flutter run
```

---

## 📚 Learning Path

### Beginner (Hari 1-2)
1. Read FLUTTER_QUICKSTART.md
2. Run `flutter run` & test login
3. Modify UI (change colors, text)
4. Test hot reload

### Intermediate (Hari 3-5)
1. Read FLUTTER_DEVELOPMENT_GUIDE.md
2. Modify ApiService (add new endpoint)
3. Create new screen & provider
4. Connect to backend

### Advanced (Hari 6+)
1. Read APK_BUILD_GUIDE.md
2. Build release APK
3. Upload ke Google Play
4. Implement advanced features

---

## 🎁 **Next Steps**

### Immediate (Now)
- [x] Run `flutter run` & test login
- [x] Explore app features
- [x] Test hot reload

### Short-term (This week)
- [ ] Build debug APK
- [ ] Install ke device
- [ ] Test all features
- [ ] Modify UI per requirement

### Medium-term (This month)
- [ ] Build release APK
- [ ] Setup Google Play account
- [ ] Submit to Play Store
- [ ] Get app reviewed

### Long-term (Next quarter)
- [ ] Add more features
- [ ] Implement backend updates
- [ ] Monitor analytics
- [ ] Gather user feedback

---

## 📞 **Quick Reference**

```bash
# Start developing
flutter run

# Build APK
flutter build apk --release

# Install APK
adb install build/app/outputs/apk/release/app-release.apk

# View logs
flutter logs

# Check devices
adb devices

# Clean build
flutter clean

# Get dependencies
flutter pub get
```

---

## 📄 **Files Mapping**

| Need | Read This File | Time |
|------|---|---|
| "Mau mulai sekarang" | FLUTTER_QUICKSTART.md | 5 min |
| "Gimana cara bikin APK?" | APK_BUILD_GUIDE.md | 20 min |
| "Pengin paham semuanya" | FLUTTER_DEVELOPMENT_GUIDE.md | 1 hour |
| "Mau bikin APK untuk Play Store" | APK_BUILD_GUIDE.md (section: Release & Signing) | 30 min |
| "Error: tidak tahu gimana" | FLUTTER_DEVELOPMENT_GUIDE.md (section: Troubleshooting) | 10 min |

---

## ✅ **Pre-launch Checklist**

**Before APK Release:**
- [ ] Test login dengan multiple accounts
- [ ] Test semua screens & navigation
- [ ] Test with poor internet connection
- [ ] Build release APK & test
- [ ] Update version in pubspec.yaml
- [ ] Verify backend URLs (production vs dev)
- [ ] Setup error logging
- [ ] Prepare app store listings

---

## 🎉 **You're All Set!**

```bash
# Sekarang Anda bisa:
✅ Develop Flutter app lokally
✅ Run dengan hot reload
✅ Build APK untuk distribution
✅ Install ke device/emulator
✅ Connect ke Laravel backend
✅ Deploy ke Google Play

# Next command:
flutter run
```

---

**Happy Coding! 🚀**

Last Updated: February 23, 2026
Version: 1.0.0

