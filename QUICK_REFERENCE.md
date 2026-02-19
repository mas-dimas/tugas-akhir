# 📌 Quick Reference Guide - Sistem Pendaftaran Perlombaan MVP

**Untuk presentasi & penjelasan cepat**

---

## 🎯 Dalam 30 Detik

> **Sistem Pendaftaran Perlombaan** adalah aplikasi web **Laravel 11** yang memungkinkan **Admin** mengelola kompetisi dan **Peserta** mendaftar dengan arsitektur yang **simple, clean, dan mudah dipahami**.

---

## 📊 Statistik Proyek

```
Database Tables: 3 (Users, Competitions, Registrations)
Models: 3
API Endpoints: 12
User Roles: 2 (Admin, Peserta)
Lines of Code: ~2500 (focused & well-documented)
Code Complexity: LOW ✅
```

---

## 🏗️ Arsitektur Dalam 1 Gambar

```
┌─────────────────────────────────────────────────────┐
│                  USER                               │
│        (Admin / Peserta)                            │
└────────┬────────────────────────────────────────────┘
         │
         │  Browser + REST API
         │
    ┌────▼────────────────────────────────────┐
    │      LARAVEL 11 API                     │
    │  - Auth (Login/Register)                │
    │  - Competition (CRUD)                  │
    │  - Registration (Join/View)             │
    └────┬────────────────────────────────────┘
         │
    ┌────▼────────────────────────────────────┐
    │        MySQL DATABASE                   │
    │  ┌─────────────────────────────────┐   │
    │  │ Users (id, name, email, role)   │   │
    │  ├─────────────────────────────────┤   │
    │  │ Competitions (title, desc, img) │   │
    │  ├─────────────────────────────────┤   │
    │  │ Registrations (user_id, comp_id)│   │
    │  └─────────────────────────────────┘   │
    └─────────────────────────────────────────┘
```

---

## 🎭 User Workflows

### Admin Workflow
```
1. Login (email: admin@example.com, pwd: password)
2. Create Perlombaan (title, desc, upload poster)
3. View all registrations
4. Logout
```

### Peserta Workflow
```
1. Register akun baru (name, email, password)
2. Login
3. Browse perlombaan yang tersedia
4. Daftar ke perlombaan yang diinginkan
5. Lihat status registrasi saya
6. Logout
```

---

## 🔌 API Endpoints (12 Total)

### 🔓 Public (No Auth)
```
POST   /auth/login                 → Login user
POST   /auth/register              → Register user
GET    /competitions               → List all competitions
GET    /competitions/{id}          → Get competition detail
```

### 🔐 Protected (With Auth Token)
```
GET    /auth/me                    → Get current user info
POST   /auth/logout                → Logout user
GET    /registrations              → My registrations
POST   /registrations              → Register to competition
GET    /registrations/{id}         → Registration detail
DELETE /registrations/{id}         → Cancel registration
GET    /admin/registrations        → All registrations (Admin)
```

---

## 📋 Database Design

### USERS Table
```sql
id       | BIGINT         (Primary Key)
name     | VARCHAR(255)   (User's name)
email    | VARCHAR(255)   (Unique, Login identifier)
password | VARCHAR(255)   (Hashed)
role     | ENUM           ('admin' or 'peserta')
created_at, updated_at | TIMESTAMP
```

### COMPETITIONS Table
```sql
id           | BIGINT       (Primary Key)
title        | VARCHAR(255) (Competition name)
description  | LONGTEXT     (Details)
poster_path  | VARCHAR(255) (Image file path)
created_at   | TIMESTAMP    (When created)
updated_at   | TIMESTAMP    (Last updated)
```

### REGISTRATIONS Table
```sql
id              | BIGINT       (Primary Key)
user_id         | BIGINT       (Foreign Key → users.id)
competition_id  | BIGINT       (Foreign Key → competitions.id)
status          | ENUM         ('submitted' - status peserta)
created_at      | TIMESTAMP
updated_at      | TIMESTAMP
UNIQUE(user_id, competition_id) ← Prevent duplicate registration
```

---

## 💻 File Structure (Esensial)

```
app/Http/Controllers/Api/
  ├── AuthController.php         (Login, Register, Logout, Me)
  ├── CompetitionController.php  (CRUD Perlombaan)
  └── RegistrationController.php (Register, View, Management)

app/Models/
  ├── User.php
  ├── Competition.php
  └── Registration.php

database/migrations/
  ├── create_users_table
  ├── create_competitions_table
  └── create_registrations_table

routes/
  └── api.php (12 endpoints)

docs/sdlc/
  ├── 01-REQUIREMENTS.md
  ├── 02-DESIGN.md
  └── 03-IMPLEMENTATION_PLAN.md
```

---

## 🚀 Deployment

### Local Development
```bash
git checkout sdlc-simplification
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
# Access: http://localhost:8000
```

### Test Credentials
```
Admin:
  email: admin@example.com
  password: password

Peserta:
  email: peserta1@example.com
  password: password
```

---

## 🔐 Security Features

✅ Password hashing (bcrypt)
✅ Sanctum API authentication  
✅ Authorization middleware (admin checks)
✅ Unique registration constraint (prevent duplicates)
✅ Input validation on all endpoints
✅ CORS configuration
✅ SQL injection prevention (Eloquent ORM)

---

## 📈 Keunggulan Desain Ini

| Aspek | Why Good |
|-------|----------|
| **Simple Schema** | Mudah dipahami, tidak ada kompleksitas DB unnecessary |
| **3 Models Only** | Fokus, easy to maintain, less bugs |
| **Type Hints** | All methods have explicit types |
| **Well Documented** | SDLC docs + code comments |
| **REST Compliant** | Standard HTTP verbs, consistent response format |
| **SOLID Principles** | Single responsibility, Models/Controllers separated |
| **Clean Code** | PSR-12 standards, naming conventions |
| **Error Handling** | Standardized error responses |

---

## 📊 Perbandingan: Before vs After

### BEFORE (Kompleks)
```
Database Tables: 5
  - users
  - competitions
  - document_templates       ← banyak
  - registrations
  - submission_documents     ← banyak

API Endpoints: 20+
Models: 5
Features: Advanced (doc upload, review, feedback)
Explanation Time: 30 mins+ ❌
```

### AFTER (MVP)
```
Database Tables: 3
  - users
  - competitions
  - registrations

API Endpoints: 12
Models: 3
Features: Core only (auth, CRUD, registration)
Explanation Time: 5-10 mins ✅
```

---

## 🎓 SDLC Phases Implemented

### Phase 1: Requirements ✅
- User Stories defined
- Functional requirements listed
- Success metrics set

### Phase 2: Design ✅
- ER Diagram created
- API Specification documented
- Security design planned

### Phase 3: Implementation ✅
- Models created
- Controllers implemented
- Routes configured
- Migrations prepared

### Phase 4-5: Testing & Deployment
- Ready for tests
- Deployment ready

---

## 🗣️ Talking Points untuk Presentasi

**Opening (30 detik)**
> "Sistem ini adalah aplikasi web untuk mengelola pendaftaran perlombaan. Saya membuat versi MVP (Minimum Viable Product) yang **sederhana namun fungsional** agar mudah dijelaskan dan dipahami."

**Data Model (1 menit)**
> "Database kami punya 3 tabel: Users untuk login, Competitions untuk daftar lomba, dan Registrations untuk track siapa yang daftar kemana. Simple!"

**Architecture (1 menit)**
> "Arsitekturnya clean MVC: Models handle data, Controllers handle logic via REST API, dan Views opsional. Semua auth pakai Sanctum token."

**Features (1 menit)**
> "Admin bisa create/edit/delete lomba, peserta bisa lihat dan daftar. Prevent duplicate daftar dengan unique constraint. Status registrasi baru submitted pada MVP."

**Benefits (30 detik)**
> "Design ini follow SDLC framework, code well-documented, easy to maintain, mudah di-extend untuk fitur baru di fase 2."

---

## 🔗 Key Links

- **Main Branch**: `main` (production version)
- **Feature Branch**: `sdlc-simplification` ← **YOU ARE HERE**
- **Documentation**: `docs/sdlc/` folder
- **MVP Readme**: `MVP_README.md`

---

## ⚡ Common Questions & Answers

**Q: Kenapa hanya 3 tables?**
A: MVP fokus pada core features. Document upload, review system bisa ditambah di Phase 2.

**Q: Bagaimana prevent duplicate registration?**
A: Dengan unique constraint pada `(user_id, competition_id)` di table registrations.

**Q: Siapa bisa akses admin endpoints?**
A: Hanya user dengan `role = 'admin'` yang dicheck via AdminMiddleware.

**Q: Berapa API endpoints?**
A: 12 endpoints total - 4 public (auth + list competitions), 6 protected (user features), 2 admin.

**Q: Bagaimana file upload poster?**
A: Multipart form-data, store di storage/public/competitions/posters, serve via Laravel storage symlink.

**Q: Production ready?**
A: Ya, tapi perlu testing comprehensive. Semua security features sudah included.

---

## 📞 Next Steps

1. **Review SDLC docs** - docs/sdlc/01-REQUIREMENTS.md
2. **Test API** - Gunakan Postman/Thunderclient
3. **Review Code** - Controllers dan Models clean dan documented
4. **Phase 2 Features** - Document upload system bisa ditambah
5. **Deployment** - Siap untuk production setelah testing

---

**Branch**: `sdlc-simplification`  
**Status**: ✅ Ready for Demonstration & Presentation  
**Last Updated**: 2026-02-19

---

*Gunakan guide ini saat presentasi atau explaining to stakeholders. Keep it simple, keep it clear! 🎉*
