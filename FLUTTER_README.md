# Flutter Mobile App - Kompetisi

Aplikasi mobile untuk sistem pendaftaran perlombaan yang terintegrasi dengan Laravel API.

## Fitur

### User (Peserta)
- ✅ Login & Registrasi
- ✅ Browse Perlombaan
- ✅ Detail Perlombaan
- ✅ Daftar Perlombaan
- ⏳ Upload Dokumen
- ⏳ Track Status Submission
- ⏳ Lihat Feedback Admin

### Admin
- ⏳ Manage Perlombaan
- ⏳ Review Peserta
- ⏳ Review Dokumen
- ⏳ Feedback Peserta

(✅ = sudah implemented, ⏳ = akan dikembangkan)

## Setup

### Prerequisites
- Flutter SDK 3.5.0 atau lebih baru
- Dart 3.5.0 atau lebih baru
- Android Studio / Xcode (untuk emulator/device)

### Installation

1. Clone repository
```bash
git clone https://github.com/mas-dimas/tugas-akhir.git
cd tugas-akhir
```

2. Switch ke mobile-app branch
```bash
git checkout mobile-app
```

3. Install dependencies
```bash
flutter pub get
```

4. Update API base URL di `lib/services/api_service.dart`
```dart
static const String baseUrl = 'http://YOUR_SERVER_IP:8000/api';
```

5. Run aplikasi
```bash
flutter run
```

## Project Structure

```
lib/
├── main.dart                 # Entry point
├── models/                   # Data models
│   ├── user.dart
│   ├── competition.dart
│   ├── registration.dart
│   └── submission_document.dart
├── services/                 # API services
│   └── api_service.dart
├── providers/                # State management (Provider)
│   ├── auth_provider.dart
│   └── competition_provider.dart
├── screens/                  # UI screens
│   ├── splash_screen.dart
│   ├── login_screen.dart
│   ├── home_screen.dart
│   ├── competition_detail_screen.dart (TODO)
│   ├── registration_screen.dart (TODO)
│   └── profile_screen.dart (TODO)
├── widgets/                  # Reusable widgets (TODO)
└── utils/                    # Utilities & constants (TODO)
```

## API Endpoints

### Authentication
- `POST /api/auth/login` - Login user
- `POST /api/auth/register` - Register user
- `POST /api/auth/logout` - Logout user

### Competitions
- `GET /api/competitions` - List semua perlombaan
- `GET /api/competitions/{id}` - Detail perlombaan

### Registrations
- `GET /api/registrations` - Daftar registrasi user
- `POST /api/registrations` - Daftar perlombaan

### User
- `GET /api/user` - Get current user info

## State Management

Menggunakan **Provider** untuk state management:

1. **AuthProvider** - Mengelola authentication, user info, login/logout
2. **CompetitionProvider** - Mengelola data perlombaan

Untuk menambah provider baru:
```dart
// lib/providers/my_provider.dart
class MyProvider extends ChangeNotifier {
  // state & methods
}

// Daftarkan di main.dart
MultiProvider(
  providers: [
    ChangeNotifierProvider(create: (_) => MyProvider()),
  ],
)
```

## API Communication

Menggunakan **http** package untuk komunikasi dengan API:

```dart
// Menggunakan ApiService
final apiService = ApiService();
final competitions = await apiService.getCompetitions();
```

Token authentication otomatis ditambahkan ke header:
```
Authorization: Bearer <token>
```

## Next Steps

1. Lengkapi screens yang masih TODO
2. Implementasi upload dokumen
3. Implementasi tracking status
4. UI/UX refinement
5. Testing & bug fixing
6. Build APK/IPA untuk release

## Development Tips

- Hot reload: `r` (untuk development)
- Hot restart: `R` (ketika perlu restart)
- Run tests: `flutter test`
- Build APK: `flutter build apk --release`
- Build iOS: `flutter build ios --release`

## Troubleshooting

### API Connection Error
- Pastikan server Laravel running
- Check API base URL di `api_service.dart`
- Untuk device real, gunakan IP address bukan localhost

### Build Error
- Clear build cache: `flutter clean`
- Get dependencies ulang: `flutter pub get`
- Rebuild: `flutter pub get && flutter run`

## References

- [Flutter Documentation](https://flutter.dev/docs)
- [Provider Package](https://pub.dev/packages/provider)
- [HTTP Package](https://pub.dev/packages/http)
- [Shared Preferences](https://pub.dev/packages/shared_preferences)
