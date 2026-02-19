# 🏆 Sistem Pendaftaran Perlombaan - MVP Edition

**Aplikasi Web untuk Mengelola Pendaftaran Perlombaan**

Branch: `sdlc-simplification` | Status: ✅ Simplified & Documented

---

## 📋 Ringkasan Proyek

Sistem Pendaftaran Perlombaan adalah aplikasi web Laravel yang dirancang dengan konsep **SDLC (Software Development Life Cycle)** untuk mengelola kompetisi dan registrasi peserta dengan arsitektur yang sederhana, mudah dipahami, dan mudah dijelaskan.

**Versi ini adalah MVP (Minimum Viable Product)** yang berfokus pada fitur inti dengan kompleksitas minimal.

---

## 🎯 Fitur & Fungsionalitas

### 📊 Skala Proyek (MVP Simplified)

| Aspek | Jumlah | Keterangan |
|-------|--------|-----------|
| **Database Tables** | 3 | Users, Competitions, Registrations |
| **Models** | 3 | User, Competition, Registration |
| **API Controllers** | 3 | AuthController, CompetitionController, RegistrationController |
| **API Endpoints** | 12 | Covered auth, CRUD, dan registrations |
| **User Roles** | 2 | Admin & Peserta |
| **Core Features** | 5 | Registration, Competition Management, View Status, Auth, Admin Dashboard |

### 🎭 User Roles & Permissions

#### **Admin**
- ✅ Login/Logout
- ✅ Create/Read/Update/Delete Perlombaan
- ✅ Upload Poster Perlombaan
- ✅ Lihat Semua Peserta yang Mendaftar
- ✅ View Registration Details

#### **Peserta**
- ✅ Register akun baru
- ✅ Login/Logout
- ✅ Lihat semua perlombaan
- ✅ View detail perlombaan
- ✅ Daftar ke perlombaan
- ✅ Lihat registrasi saya
- ✅ Cancel pendaftaran

---

## 🏗️ Arsitektur Sistem

### Database Schema (Simplified)

```
┌─────────────────────────────────────────────────────┐
│                    USERS                            │
│  id | name | email | password | role | timestamps  │
└─────────────────────────────────────────────────────┘
         │                                    │
         │ 1:M                                │
         │                              (admin/peserta)
         │
    ┌────▼──────────────────────────────────────┐
    │           REGISTRATIONS                   │
    │  id | user_id | competition_id | status   │
    │  timestamps | UNIQUE(user_id, comp_id)    │
    └────┬──────────────────────────────────────┘
         │
         │ M:1
         │
┌────────▼──────────────────────────────────────────┐
│           COMPETITIONS                            │
│  id | title | description | poster_path|timestamps│
└───────────────────────────────────────────────────┘
```

### Project Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php          (Login, Register, Logout, Me)
│   │       ├── CompetitionController.php   (CRUD Perlombaan)
│   │       └── RegistrationController.php  (Register, View, Cancel)
│   ├── Middleware/
│   │   └── AdminMiddleware.php             (Admin Authorization)
├── Models/
│   ├── User.php
│   ├── Competition.php
│   └── Registration.php
│
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2025_11_30_053130_create_competitions_table.php
│   └── 2025_11_30_061524_create_registrations_table.php
├── seeders/
│   └── DatabaseSeeder.php

routes/
├── api.php                  (REST API endpoints - 12 routes)
├── web.php                  (Web routes - minimal)
│
docs/sdlc/
├── 01-REQUIREMENTS.md       (MVP Requirements & User Stories)
├── 02-DESIGN.md             (Database Design & API Specification)
└── 03-IMPLEMENTATION_PLAN.md (Development Roadmap & Checklist)
```

---

## 🔌 REST API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/auth/login` | ❌ | Login user |
| POST | `/auth/register` | ❌ | Register new user |
| GET | `/auth/me` | ✅ | Get current user info |
| POST | `/auth/logout` | ✅ | Logout user |

### Competition Endpoints

| Method | Endpoint | Auth | Role | Description |
|--------|----------|------|------|-------------|
| GET | `/competitions` | ❌ | Any | List all competitions |
| GET | `/competitions/{id}` | ❌ | Any | Get competition detail |
| POST | `/competitions` | ✅ | Admin | Create competition |
| PUT | `/competitions/{id}` | ✅ | Admin | Update competition |
| DELETE | `/competitions/{id}` | ✅ | Admin | Delete competition |

### Registration Endpoints

| Method | Endpoint | Auth | Role | Description |
|--------|----------|------|------|-------------|
| GET | `/registrations` | ✅ | Peserta | Get my registrations |
| POST | `/registrations` | ✅ | Peserta | Register to competition |
| GET | `/registrations/{id}` | ✅ | Peserta/Admin | Get registration detail |
| DELETE | `/registrations/{id}` | ✅ | Peserta/Admin | Cancel registration |
| GET | `/admin/registrations` | ✅ | Admin | Get all registrations |

**Total: 12 API Endpoints**

### API Response Format

#### Success Response (2xx)
```json
{
    "success": true,
    "message": "Operation successful",
    "data": { /* response data */ },
    "pagination": { /* optional pagination data */ }
}
```

#### Error Response (4xx, 5xx)
```json
{
    "success": false,
    "message": "Error description",
    "errors": { /* validation errors */ }
}
```

---

## 🚀 Quick Start Guide

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & npm

### 1. Clone Repository & Setup

```bash
# Clone
git clone https://github.com/mas-dimas/tugas-akhir.git
cd tugas-akhir

# Checkout branch
git checkout sdlc-simplification

# Install dependencies
composer install
npm install
```

### 2. Environment Configuration

```bash
# Create .env file
cp .env.example .env

# Generate app key
php artisan key:generate
```

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tugas_akhir
DB_USERNAME=root
DB_PASSWORD=

APP_URL=http://localhost:8000
SANCTUM_STATEFUL_DOMAINS=localhost
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed test data (optional)
php artisan db:seed

# Create storage link for file uploads
php artisan storage:link
```

### 4. Build Assets

```bash
npm run dev
# or for production
npm run build
```

### 5. Run Application

```bash
php artisan serve
```

Access: **http://localhost:8000**

---

## 👤 Test Credentials

After running seeder:

#### Admin Account
- Email: `admin@example.com`
- Password: `password`
- Role: `admin`

#### Peserta Account
- Email: `peserta1@example.com`
- Password: `password`
- Role: `peserta`

---

## 📚 API Documentation

### Example: User Registration

```bash
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}

Response (201):
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 2,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "peserta"
        },
        "token": "1|abc1234def5678..."
    }
}
```

### Example: Login

```bash
POST /api/auth/login
Content-Type: application/json

{
    "email": "peserta1@example.com",
    "password": "password"
}

Response (200):
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Peserta Satu",
            "email": "peserta1@example.com",
            "role": "peserta"
        },
        "token": "2|xyz9876abc1234..."
    }
}
```

### Example: Get All Competitions

```bash
GET /api/competitions

Response (200):
{
    "success": true,
    "message": "List of competitions",
    "data": [
        {
            "id": 1,
            "title": "Kompetisi Coding 2026",
            "description": "Lomba programming tingkat nasional...",
            "poster_url": "http://localhost:8000/storage/competitions/posters/...",
            "registration_count": 25,
            "created_at": "2026-02-19T12:00:00Z"
        }
    ],
    "pagination": {
        "total": 10,
        "per_page": 15,
        "current_page": 1,
        "last_page": 1
    }
}
```

### Example: Register to Competition

```bash
POST /api/registrations
Authorization: Bearer {token}
Content-Type: application/json

{
    "competition_id": 1
}

Response (201):
{
    "success": true,
    "message": "Successfully registered to competition",
    "data": {
        "id": 5,
        "competition_id": 1,
        "status": "submitted",
        "created_at": "2026-02-19T13:45:00Z"
    }
}
```

---

## 📖 SDLC Documentation

Proyek ini mengikuti **Software Development Life Cycle (SDLC)** dengan dokumentasi lengkap:

### Phase 1: Requirements ✅
- Dokumen: [docs/sdlc/01-REQUIREMENTS.md](docs/sdlc/01-REQUIREMENTS.md)
- Mencakup: User stories, functional requirements, acceptance criteria

### Phase 2: Design ✅
- Dokumen: [docs/sdlc/02-DESIGN.md](docs/sdlc/02-DESIGN.md)
- Mencakup: ER diagram, API specification, security design, testing strategy

### Phase 3: Implementation ✅
- Dokumen: [docs/sdlc/03-IMPLEMENTATION_PLAN.md](docs/sdlc/03-IMPLEMENTATION_PLAN.md)
- Mencakup: Task breakdown, code standards, configuration, deployment prep

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

### Test Coverage Areas
- ✅ Authentication (login, register, logout)
- ✅ Authorization (admin middleware)
- ✅ Competition CRUD
- ✅ Registration management
- ✅ Validation
- ✅ Error handling

---

## 📊 Code Structure & Standards

### Naming Conventions
```
Models: PascalCase (User, Competition, Registration)
Methods: camelCase (getCompetitions, createRegistration)
Variables: snake_case ($user_id, $competition_id)
Constants: UPPER_SNAKE_CASE (STATUS_SUBMITTED)
Routes: kebab-case (/api/competitions, /api/registrations)
```

### Code Style
- PSR-12 standards
- 4-space indentation  
- Type hints on all methods
- Comprehensive documentation comments

### Example Controller Method

```php
/**
 * Get all competitions with pagination
 *
 * @param Request $request
 * @return JsonResponse
 */
public function index(Request $request): JsonResponse
{
    $perPage = $request->get('per_page', 15);
    $competitions = Competition::paginate($perPage);

    return response()->json([
        'success' => true,
        'message' => 'List of competitions',
        'data' => $competitions->items(),
        'pagination' => [
            'total' => $competitions->total(),
            'per_page' => $competitions->perPage(),
            'current_page' => $competitions->currentPage(),
        ],
    ], 200);
}
```

---

## 🔐 Security Features

- ✅ Password hashing (bcrypt)
- ✅ CSRF protection
- ✅ Sanctum token authentication
- ✅ Authorization middleware (admin checks)
- ✅ Input validation
- ✅ SQL injection prevention (ORM)
- ✅ Rate limiting (can be configured)
- ✅ CORS configuration

---

## 📈 Performance Optimization

- Database indexing on foreign keys
- Eager loading relationships (with())
- Pagination for large datasets
- Query optimization
- Caching ready

---

## 🚀 Deployment Guidelines

### Pre-Deployment Checklist
- [ ] All tests passing
- [ ] Environment variables configured
- [ ] Database migrated
- [ ] Storage link created
- [ ] Assets built (npm run build)
- [ ] Error logging configured

### Production Environment

```bash
# Build for production
npm run build

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run application
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 📝 Changelog

### v1.0.0 (MVP - 2026-02-19)
- ✅ SDLC documentation
- ✅ Simplified database schema (3 tables)
- ✅ Complete REST API (12 endpoints)
- ✅ Authentication & Authorization
- ✅ Competition management
- ✅ Registration system
- ✅ File upload for posters
- ✅ API documentation
- ✅ Test coverage

---

## 🗺️ Roadmap (Phase 2+)

### Soon (Phase 2)
- [ ] Document upload & review system
- [ ] Email notifications
- [ ] Advanced search & filtering
- [ ] Dashboard statistics

### Future (Phase 3+)
- [ ] Payment integration
- [ ] Mobile app (Flutter)
- [ ] Real-time notifications
- [ ] Advanced reporting & analytics

---

## 🤝 Kontribusi

Untuk kontribusi, silakan:
1. Fork repository
2. Buat feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

---

## 📞 Support & Help

- 📖 Baca SDLC documentation di folder `docs/sdlc/`
- 📧 Email: [your-email]
- 💬 GitHub Issues: Report bugs atau suggest features

---

## 📄 License

This project is open source and available under the MIT License.

---

## ✨ Highlights

**Mengapa proyek ini mudah dipahami & dijelaskan?**

1. ✅ **Schema sederhana**: Hanya 3 table dengan relations yang jelas
2. ✅ **SDLC framework**: Terstruktur mengikuti standar industri
3. ✅ **API yang clean**: 12 endpoint yang RESTful & konsisten
4. ✅ **Code well-documented**: Setiap method memiliki documentation
5. ✅ **Separated concerns**: Models, Controllers, Services terisolasi dengan baik
6. ✅ **Type hints**: Semua parameter dan return types explicit
7. ✅ **Error handling**: Standardized error responses
8. ✅ **No unnecessary complexity**: Feature focused, minimal bloat

---

**Branch**: `sdlc-simplification`  
**Last Updated**: 2026-02-19  
**Status**: ✅ Production Ready (MVP)

Siap untuk dipresentasikan dan dijelaskan dengan lancar! 🎉
