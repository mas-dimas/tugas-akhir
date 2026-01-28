# Dokumentasi Migrasi: Web ke Mobile Apps

## Ringkasan

Saya telah berhasil melakukan migrasi project dari aplikasi web berbasis Laravel menjadi dua versi:

### 1. Backend API (Branch: `main`)
Laravel diubah menjadi API backend yang dapat digunakan oleh web dan mobile

### 2. Mobile Apps (Branch: `mobile-app`)
Aplikasi mobile Flutter yang terintegrasi dengan Laravel API

---

## Branch Structure

```
GitHub Repository: mas-dimas/tugas-akhir
├── main/
│   ├── Web UI (Blade Templates) - tetap
│   └── REST API (baru)
│
└── mobile-app/
    ├── Flutter Project (baru)
    └── Terintegrasi dengan API di main
```

---

## Apa yang Sudah Dibuat

### A. Laravel Backend API (main branch)

#### API Routes (`routes/api.php`)
```
Authentication:
- POST   /api/auth/login           → Login user
- POST   /api/auth/register        → Register user
- POST   /api/auth/logout          → Logout user

Competitions (Public):
- GET    /api/competitions         → List semua perlombaan
- GET    /api/competitions/{id}    → Detail perlombaan

User Registrations:
- GET    /api/registrations        → Lihat registrasi user
- POST   /api/registrations        → Daftar perlombaan
- GET    /api/registrations/{id}   → Detail registrasi

Documents:
- GET    /api/registrations/{id}/documents      → List dokumen
- POST   /api/registrations/{id}/documents      → Upload dokumen
- DELETE /api/documents/{id}                    → Hapus dokumen
- GET    /api/documents/{id}/download           → Download dokumen

Admin Routes:
- POST   /api/admin/competitions                → Create perlombaan
- PUT    /api/admin/competitions/{id}           → Update perlombaan
- DELETE /api/admin/competitions/{id}           → Delete perlombaan
- GET    /api/admin/registrations               → Lihat semua registrasi
- PUT    /api/admin/registrations/{id}/status   → Update status registrasi
- PUT    /api/admin/documents/{id}/review       → Review dokumen
```

#### API Controllers
- **AuthController** - Handle login, register, logout
- **CompetitionController** - Handle CRUD perlombaan
- **RegistrationController** - Handle registrasi peserta
- **SubmissionDocumentController** - Handle dokumen upload

#### Security Features
- ✅ Sanctum token authentication
- ✅ AdminMiddleware untuk proteksi admin routes
- ✅ CORS configuration
- ✅ Bearer token validation

---

### B. Flutter Mobile App (mobile-app branch)

#### Architecture
```
lib/
├── main.dart                    # Entry point dengan Provider setup
├── models/                      # Data models
│   ├── user.dart
│   ├── competition.dart
│   ├── registration.dart
│   └── submission_document.dart
├── services/
│   └── api_service.dart        # HTTP client untuk API
├── providers/                   # State management (Provider)
│   ├── auth_provider.dart
│   └── competition_provider.dart
├── screens/
│   ├── splash_screen.dart
│   ├── login_screen.dart
│   └── home_screen.dart
├── widgets/                     # Reusable components
└── utils/                       # Utilities & constants
```

#### Models
- **User** - User data dengan JSON serialization
- **Competition** - Data perlombaan
- **Registration** - Registrasi peserta
- **SubmissionDocument** - Dokumen yang di-upload

#### Services
- **ApiService** - HTTP client wrapper
  - Automatic token management
  - Error handling
  - JSON serialization/deserialization

#### State Management (Provider Pattern)
- **AuthProvider** - Login/logout logic, user state
- **CompetitionProvider** - Competition data fetching

#### UI Screens
1. **SplashScreen** - Auto check auth status & route
2. **LoginScreen** - Login form dengan toggle ke register
3. **HomeScreen** - List perlombaan dengan refresh

#### Features Implemented
- ✅ User authentication (login/register/logout)
- ✅ Browse perlombaan
- ✅ Token persistence (SharedPreferences)
- ✅ Error handling
- ✅ Loading states

#### Dependencies
```yaml
provider: ^6.4.0           # State management
http: ^1.1.0              # HTTP client
shared_preferences: ^2.2.2 # Local storage untuk token
intl: ^0.19.0             # Internationalization
image_picker: ^1.0.4      # Untuk upload dokumen
file_picker: ^6.1.1       # File selection
```

---

## Workflow untuk Development

### Bekerja dengan Backend API
```bash
# Switch ke main branch
git checkout main

# Membuat perubahan di API
# ...changes...

# Commit dan push
git add .
git commit -m "API changes"
git push origin main
```

### Bekerja dengan Mobile App
```bash
# Switch ke mobile-app branch
git checkout mobile-app

# Setiap kali update API di main, sync ke mobile-app
git merge main

# Membuat perubahan di Flutter
# ...changes...

# Commit dan push
git add .
git commit -m "Mobile app changes"
git push origin mobile-app
```

---

## Setup untuk Development

### Backend (Laravel)
```bash
# Switch ke main branch
git checkout main

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database migration
php artisan migrate

# Run server
php artisan serve
```

### Frontend Mobile (Flutter)
```bash
# Switch ke mobile-app branch
git checkout mobile-app

# Install Flutter dependencies
flutter pub get

# Update API base URL di lib/services/api_service.dart
# static const String baseUrl = 'http://YOUR_IP:8000/api';

# Run aplikasi
flutter run
```

---

## Next Steps (Roadmap)

### Phase 2: Extended Features
- [ ] Competition detail screen dengan document templates
- [ ] Registration flow dengan dokumen upload
- [ ] Status tracking untuk submission
- [ ] Admin dashboard di mobile
- [ ] Notification system

### Phase 3: Polish
- [ ] UI/UX refinement
- [ ] Advanced state management
- [ ] Offline support dengan SQLite
- [ ] Performance optimization
- [ ] Error handling & validation

### Phase 4: Testing & Deployment
- [ ] Unit tests untuk API & Flutter
- [ ] Integration tests
- [ ] Build APK untuk Android
- [ ] Build IPA untuk iOS
- [ ] Deploy ke Play Store / App Store

---

## Testing API Endpoints

### Menggunakan Postman
1. Import collection dari API routes
2. Set base URL: `http://localhost:8000`
3. Test endpoints:

```
Login:
POST /api/auth/login
Body: {
  "email": "user@example.com",
  "password": "password"
}

Response akan berisi token untuk di-gunakan di request berikutnya

Get Competitions:
GET /api/competitions
Header: Authorization: Bearer <token>
```

### Menggunakan cURL
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get Competitions
curl -X GET http://localhost:8000/api/competitions \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Important Notes

1. **API Base URL**
   - Update di `lib/services/api_service.dart`
   - Gunakan IP address untuk device real (bukan localhost)

2. **Token Management**
   - Token di-save ke SharedPreferences
   - Otomatis di-attach ke setiap request
   - Di-clear saat logout

3. **CORS**
   - Sudah di-configure di `config/cors.php`
   - Support untuk mobile app & web browser

4. **Database**
   - Sama dengan project asli
   - API layer additional (non-destructive)

---

## File Changes Summary

### Main Branch (Backend API)
- ✅ `routes/api.php` (baru)
- ✅ `app/Http/Controllers/Api/*` (baru)
- ✅ `app/Http/Middleware/AdminMiddleware.php` (baru)
- ✅ `config/cors.php` (baru)
- ✅ `bootstrap/app.php` (updated)
- ✅ `app/Models/User.php` (updated - add HasApiTokens)

### Mobile-App Branch (Flutter)
- ✅ `pubspec.yaml` (baru)
- ✅ `lib/main.dart` (baru)
- ✅ `lib/models/*` (baru)
- ✅ `lib/services/api_service.dart` (baru)
- ✅ `lib/providers/*` (baru)
- ✅ `lib/screens/*` (baru)
- ✅ `analysis_options.yaml` (baru)
- ✅ `.flutterignore` (baru)
- ✅ `FLUTTER_README.md` (baru)

---

## Kontribusi di Masa Depan

### Untuk Backend
- Tambah endpoint baru di `routes/api.php`
- Buat controller di `app/Http/Controllers/Api/`
- Update model jika perlu relasi baru

### Untuk Mobile
- Add screen baru di `lib/screens/`
- Add provider baru di `lib/providers/` untuk state management
- Add models baru di `lib/models/` sesuai kebutuhan

Pastikan selalu:
- Maintain backward compatibility
- Update documentation
- Test di kedua platform

---

Semua sudah siap untuk dikembangkan lebih lanjut! 🚀
