# Phase 2: Design (SDLC)

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    CLIENT LAYER                             │
│                  (Web Browser / Vue.js)                     │
└────────────────────┬────────────────────┬───────────────────┘
                     │                    │
           ┌─────────▼──────┐   ┌────────▼─────────┐
           │   Web Routes   │   │   API Routes     │
           │   (Blade)      │   │   (REST API)     │
           └─────────┬──────┘   └────────┬─────────┘
                     │                   │
                     └───────┬──────┬────┘
                             │      │
        ┌────────────────────▼──────▼────────────────────┐
        │         APPLICATION LAYER                      │
        │  Controllers → Services → Repositories         │
        └────────────────────┬─────────────────────────────┘
                             │
        ┌────────────────────▼─────────────────────────────┐
        │         DATABASE LAYER                          │
        │  (MySQL - 3 Tables)                            │
        └─────────────────────────────────────────────────┘
```

---

## 📊 Database Design (ER Diagram)

### Simplified Entity Relationship

```
┌──────────────────────┐
│      USERS           │
├──────────────────────┤
│ id (PK)              │
│ name                 │
│ email (UNIQUE)       │
│ password (hashed)    │
│ role (enum)          │  ◄─────┐
│ created_at           │        │
│ updated_at           │        │
└──────────────────────┘        │
         │                       │
         │ 1:M                   │
         │                       │
    ┌────▼──────────────────────┴──────────────┐
    │      REGISTRATIONS                       │
    ├──────────────────────────────────────────┤
    │ id (PK)                                  │
    │ user_id (FK) → users.id                  │
    │ competition_id (FK) → competitions.id    │
    │ status (enum: 'submitted')               │
    │ created_at                               │
    │ updated_at                               │
    │ UNIQUE(user_id, competition_id)          │
    └─────────────────────┬─────────────────────┘
                          │
                          │ M:1
                          │
        ┌─────────────────▼──────────────────┐
        │   COMPETITIONS                     │
        ├────────────────────────────────────┤
        │ id (PK)                            │
        │ title                              │
        │ description                        │
        │ poster_path                        │
        │ created_at                         │
        │ updated_at                         │
        └────────────────────────────────────┘
```

### Table Schemas (SQL)

```sql
-- TABLE: Users
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'peserta') DEFAULT 'peserta',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: Competitions
CREATE TABLE competitions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    poster_path VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: Registrations
CREATE TABLE registrations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    competition_id BIGINT UNSIGNED NOT NULL,
    status ENUM('submitted') DEFAULT 'submitted',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_registration (user_id, competition_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (competition_id) REFERENCES competitions(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_competition_id (competition_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Removed Tables:**
- ❌ `document_templates` - Not needed in MVP
- ❌ `submission_documents` - Advanced feature for Phase 2
- ✅ `cache` - Framework default (unchanged)
- ✅ `jobs` - Framework default (unchanged)

---

## 🔌 REST API Design

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication
```
Type: Bearer Token (Sanctum)
Header: Authorization: Bearer {token}
```

### Standard Response Format

#### Success Response (2xx)
```json
{
    "success": true,
    "data": { /* response data */ },
    "message": "Operation successful"
}
```

#### Error Response (4xx, 5xx)
```json
{
    "success": false,
    "error": "ERROR_CODE",
    "message": "Error description",
    "errors": { /* validation errors */ }
}
```

---

## 📋 API Endpoints Specification

### 1. Authentication Endpoints

#### POST /api/v1/auth/register
**Purpose**: User registration
```
Request:
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}

Response (201):
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "peserta"
        },
        "token": "1|abc...xyz"
    }
}
```

#### POST /api/v1/auth/login
**Purpose**: User login
```
Request:
{
    "email": "admin@example.com",
    "password": "password"
}

Response (200):
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "role": "admin"
        },
        "token": "1|abc...xyz"
    }
}
```

#### GET /api/v1/auth/me
**Purpose**: Get current authenticated user
**Auth**: Required (Bearer Token)
```
Response (200):
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "peserta"
    }
}
```

#### POST /api/v1/auth/logout
**Purpose**: User logout
**Auth**: Required (Bearer Token)
```
Response (200):
{
    "success": true,
    "message": "Logout successful"
}
```

### 2. Competition Endpoints

#### GET /api/v1/competitions
**Purpose**: List all competitions (public)
```
Query Params:
- page: integer (default: 1)
- per_page: integer (default: 15)
- search: string (optional)

Response (200):
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Kompetisi Coding 2026",
            "description": "...",
            "poster_url": "http://...",
            "created_at": "2026-02-19T...",
            "registration_count": 25
        }
    ],
    "pagination": {
        "total": 50,
        "per_page": 15,
        "current_page": 1
    }
}
```

#### GET /api/v1/competitions/{id}
**Purpose**: Get competition detail (public)
```
Response (200):
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Kompetisi Coding 2026",
        "description": "...",
        "poster_url": "http://...",
        "created_at": "2026-02-19T...",
        "registration_count": 25,
        "registered": true/false  // if user is logged in
    }
}
```

#### POST /api/v1/competitions (Admin Only)
**Purpose**: Create new competition
**Auth**: Required (Admin role)
```
Request (multipart/form-data):
{
    "title": "Kompetisi Coding 2026",
    "description": "...",
    "poster": <file>
}

Response (201):
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Kompetisi Coding 2026",
        "poster_url": "http://..."
    }
}
```

#### PUT /api/v1/competitions/{id} (Admin Only)
**Purpose**: Update competition
**Auth**: Required (Admin role)
```
Request (multipart/form-data):
{
    "title": "Updated Title",
    "description": "...",
    "poster": <file> (optional)
}

Response (200):
{
    "success": true,
    "data": { /* updated competition */ }
}
```

#### DELETE /api/v1/competitions/{id} (Admin Only)
**Purpose**: Delete competition
**Auth**: Required (Admin role)
```
Response (200):
{
    "success": true,
    "message": "Competition deleted"
}
```

### 3. Registration Endpoints

#### GET /api/v1/registrations
**Purpose**: Get current user's registrations
**Auth**: Required
```
Response (200):
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user_id": 2,
            "competition_id": 1,
            "status": "submitted",
            "competition": {
                "id": 1,
                "title": "Kompetisi Coding 2026"
            },
            "created_at": "2026-02-19T..."
        }
    ]
}
```

#### POST /api/v1/registrations
**Purpose**: Register to a competition
**Auth**: Required
```
Request:
{
    "competition_id": 1
}

Response (201):
{
    "success": true,
    "data": {
        "id": 1,
        "competition_id": 1,
        "status": "submitted"
    },
    "message": "Successfully registered"
}

Error (409):
{
    "success": false,
    "error": "ALREADY_REGISTERED",
    "message": "You already registered to this competition"
}
```

#### GET /api/v1/registrations/{id}
**Purpose**: Get registration detail
**Auth**: Required (Owner or Admin)
```
Response (200):
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 2,
        "competition_id": 1,
        "status": "submitted",
        "user": { /* user data */ },
        "competition": { /* competition data */ },
        "created_at": "2026-02-19T..."
    }
}
```

#### DELETE /api/v1/registrations/{id}
**Purpose**: Cancel registration
**Auth**: Required (Owner or Admin)
```
Response (200):
{
    "success": true,
    "message": "Registration cancelled"
}
```

#### GET /api/v1/admin/registrations (Admin Only)
**Purpose**: Get all registrations
**Auth**: Required (Admin role)
```
Query Params:
- competition_id: integer (optional)
- status: string (optional)
- page: integer (default: 1)

Response (200):
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user": {
                "id": 2,
                "name": "John Doe",
                "email": "john@example.com"
            },
            "competition": {
                "id": 1,
                "title": "Kompetisi Coding 2026"
            },
            "status": "submitted",
            "created_at": "2026-02-19T..."
        }
    ]
}
```

---

## 🏢 Application Layer Design

### Folder Structure
```
app/
├── Http/
│   └── Controllers/
│       ├── Api/
│       │   ├── AuthController.php
│       │   ├── CompetitionController.php
│       │   └── RegistrationController.php
│       └── Controller.php (base)
├── Models/
│   ├── User.php
│   ├── Competition.php
│   └── Registration.php
├── Services/  (Business Logic)
│   ├── AuthService.php
│   ├── CompetitionService.php
│   └── RegistrationService.php
└── Http/
    └── Middleware/
        └── AdminMiddleware.php
```

### Design Patterns Used
1. **MVC Pattern**: Separation of concerns
2. **Service Layer**: Business logic encapsulation
3. **Repository Pattern** (optional): Data access abstraction
4. **Middleware Pattern**: Request filtering (Auth, Admin check)

---

## 🔐 Security Design

### Authentication Flow
```
1. User Login
   ├─ Verify email & password
   ├─ Generate Sanctum token
   └─ Return token to client

2. API Request
   ├─ Client sends Bearer token
   ├─ Verify token validity
   ├─ Check user role if needed
   └─ Execute request

3. Logout
   ├─ Revoke token
   └─ Clear session
```

### Authorization Rules
```
Public Endpoints:
- GET /api/competitions (anyone)
- GET /api/competitions/{id} (anyone)
- POST /api/auth/login (anyone)
- POST /api/auth/register (anyone)

Protected Endpoints (Authenticated Users):
- GET /api/registrations (own data only)
- POST /api/registrations (any peserta)
- DELETE /api/registrations/{id} (owner or admin)
- GET /api/auth/me (self)
- POST /api/auth/logout (self)

Admin Only Endpoints:
- POST /api/competitions (admin)
- PUT /api/competitions/{id} (admin)
- DELETE /api/competitions/{id} (admin)
- GET /api/admin/registrations (admin)
```

---

## 🧪 Testing Strategy

### Unit Tests
- Service layer logic
- Model validations
- Helper functions

### Feature Tests
- API endpoints
- Authentication flows
- Authorization checks
- Business logic scenarios

### Test Coverage Goals
- Minimum 80% code coverage
- All critical paths tested
- Edge cases covered

---

## 📝 Implementation Checklist

- [ ] Create Models (User, Competition, Registration)
- [ ] Create Controllers (AuthController, CompetitionController, RegistrationController)
- [ ] Create Services (AuthService, CompetitionService, RegistrationService)
- [ ] Create Routes (routes/api.php)
- [ ] Create Migrations (users, competitions, registrations)
- [ ] Create Seeders (test data)
- [ ] Implement Authentication (Sanctum)
- [ ] Implement Authorization (AdminMiddleware)
- [ ] Add File Upload handling (posters)
- [ ] Write Tests
- [ ] API Documentation

---

## ✅ Design Review Checklist

- ✅ Database design is normalized
- ✅ API design follows REST principles
- ✅ Security considerations are addressed
- ✅ Scalability is considered
- ✅ Error handling is planned
- ✅ No unnecessary complexity

---

## 📌 Sign-Off

- **Date**: 2026-02-19
- **Status**: Ready for Implementation Phase
