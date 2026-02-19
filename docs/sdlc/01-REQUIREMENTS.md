# Phase 1: Requirements (SDLC)

## 📋 Project Overview

**Sistem Pendaftaran Perlombaan - Simplified MVP Version**

Aplikasi web untuk mengelola pendaftaran perlombaan dengan konsep minimal namun fungsional. Tujuan utama adalah demonstrasi yang jelas dan mudah dipahami.

---

## 🎯 Functional Requirements

### A. User Roles
```
1. Admin
   - Manage perlombaan (CRUD)
   - View peserta yang mendaftar
   
2. Peserta
   - Lihat daftar perlombaan
   - Daftar ke perlombaan
   - Lihat status registrasi
```

### B. Core Features

#### Admin Features
```
✅ Authentication
  - Login dengan email & password
  - Logout
  - Session management

✅ Competition Management
  - Create perlombaan (title, description, poster)
  - View semua perlombaan
  - Update perlombaan
  - Delete perlombaan
  - Upload poster image

✅ View Registrations
  - Lihat daftar peserta per perlombaan
  - Lihat informasi peserta
  - View registration status
```

#### Peserta Features
```
✅ Authentication
  - Register akun baru
  - Login
  - Logout

✅ Browse Competitions
  - Lihat semua perlombaan
  - View detail perlombaan
  - Download poster

✅ Registration
  - Daftar ke perlombaan
  - View registrasi saya
  - Lihat status registrasi (submitted)
```

---

## 🏗️ Non-Functional Requirements

| Requirement | Details |
|------------|---------|
| **Performance** | Response time < 2 detik |
| **Security** | Password hashing, SQL injection prevention |
| **Scalability** | Support ~1000 users |
| **Availability** | 99% uptime |
| **Usability** | Simple UI, mudah digunakan |
| **Maintainability** | Clean code, documented |

---

## 📊 Database Requirements

### Entities (Simplified)

```
1. Users
   - id (PK)
   - name
   - email (UNIQUE)
   - password
   - role (admin/peserta)
   - created_at, updated_at

2. Competitions
   - id (PK)
   - title
   - description
   - poster_path
   - created_at, updated_at

3. Registrations
   - id (PK)
   - user_id (FK)
   - competition_id (FK)
   - status (submitted)
   - created_at, updated_at
   - UNIQUE(user_id, competition_id) - prevent duplicate registration

Total: 3 tables (simplified dari 5 tables)
```

**Removed/Simplified:**
- ❌ DocumentTemplate (tidak diperlukan di MVP)
- ❌ SubmissionDocument (fitur advanced, fase 2)
- ✅ Registrations (dipangkas, hanya status)

---

## 🔌 API Requirements

### Authentication Endpoints
```
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
GET  /api/auth/me
```

### Competition Endpoints
```
GET    /api/competitions              (public)
GET    /api/competitions/{id}         (public)
POST   /api/competitions              (admin only)
PUT    /api/competitions/{id}        (admin only)
DELETE /api/competitions/{id}         (admin only)
```

### Registration Endpoints
```
GET    /api/registrations              (user's registrations)
POST   /api/registrations              (register to competition)
GET    /api/registrations/{id}         (detail)
GET    /api/admin/registrations        (admin - view all)
DELETE /api/registrations/{id}         (cancel registration)
```

**Total: 12 API endpoints (minimal)**

---

## 🎭 User Stories

### Admin User Stories
```
Story 1: Login
  AS AN admin
  I WANT TO login with email and password
  SO THAT I can access admin dashboard

Story 2: Create Competition
  AS AN admin
  I WANT TO create new competition
  SO THAT peserta can register

Story 3: View Registrations
  AS AN admin
  I WANT TO view all registrations
  SO THAT I know who registered

Story 4: Logout
  AS AN admin
  I WANT TO logout
  SO THAT my session is closed
```

### Peserta User Stories
```
Story 1: Register Account
  AS A peserta
  I WANT TO register a new account
  SO THAT I can access the system

Story 2: Browse Competitions
  AS A peserta
  I WANT TO see all available competitions
  SO THAT I can choose which to register

Story 3: Register to Competition
  AS A peserta
  I WANT TO register to a competition
  SO THAT I can participate

Story 4: View My Status
  AS A peserta
  I WANT TO see my registration status
  SO THAT I know if I'm registered

Story 5: Logout
  AS A peserta
  I WANT TO logout
  SO THAT my session is closed
```

---

## 📋 Acceptance Criteria (MVP)

1. **Authentication** ✅
   - User dapat login dengan email dan password yang benar
   - User tidak dapat login dengan credentials yang salah
   - Session harus timeout setelah inactivity

2. **Competition Management** ✅
   - Admin dapat create competition
   - Competition tampil di list
   - Peserta dapat melihat competition list
   - Admin dapat update dan delete

3. **Registration** ✅
   - Peserta dapat register to competition
   - Peserta tidak bisa register 2x ke perlombaan yang sama
   - Admin dapat view semua registrations

4. **User Interface** ✅
   - Clean dan intuitif
   - Mobile responsive
   - Fast loading

---

## 🚫 Out of Scope (Fase 2+)

- Document upload dan review
- Email notifications
- Payment system
- Rating/review system
- Advanced reporting
- Mobile app (Flutter)
- Real-time features

---

## ✅ Success Metrics

| Metric | Target |
|--------|--------|
| Page load time | < 2 detik |
| User can register in | < 1 menit |
| 95% test coverage | Covered |
| Code maintainability | SonarCube score > 80 |
| Documentation coverage | 100% |

---

## 📅 Timeline

- **Phase 1: Requirements** (1 day) ✅ CURRENT
- **Phase 2: Design** (1-2 days)
- **Phase 3: Implementation** (3-4 days)
- **Phase 4: Testing** (1-2 days)
- **Phase 5: Documentation** (1 day)

---

## 📌 Sign-Off

- **Project Manager**: [Student Name]
- **Date**: 2026-02-19
- **Approved**: Ready for Design Phase
