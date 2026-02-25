# 🛠️ TECHNICAL DOCUMENTATION
## Sistem Pendaftaran Perlombaan - Technical Details

---

## 📑 Daftar Isi

1. [Tech Stack Details](#tech-stack-details)
2. [Laravel Architecture](#laravel-architecture)
3. [API Documentation](#api-documentation)
4. [Database Schema Details](#database-schema-details)
5. [Code Samples](#code-samples)
6. [Performance Optimization](#performance-optimization)
7. [Security Implementation](#security-implementation)
8. [Testing Strategy](#testing-strategy)

---

## 🛠️ Tech Stack Details

### Backend Technologies

#### Laravel 11
```
Version: ^12.0 (Latest)
Type: Full-stack PHP framework
Use Case: REST API + Web application
Key Features:
- Eloquent ORM for database interaction
- Laravel Sanctum for API authentication
- Built-in request validation
- Middleware support
- Database migrations
```

#### PHP 8.2+
```
Features Used:
- Typed properties & return types
- Named arguments
- Match expressions
- Nullsafe operator
- Constructor property promotion
```

#### MySQL 8.0+
```
Features Used:
- InnoDB storage engine
- Foreign key constraints
- Unique indexes
- Cascading deletes
- JSON support (future enhancement)
```

### Frontend Technologies

#### Blade Templates
```
Laravel's templating engine
Features:
- Directive syntax: @if, @foreach, @csrf
- Component system: @component()
- Layout inheritance: @extends, @section
- Built-in PHP support
Usage: Server-side rendering
```

#### Tailwind CSS
```
Version: ^3.1.0
Utility-first CSS framework
Usage:
- Responsive design
- Component styling
- Dark mode support
- Custom configuration
```

#### Alpine.js
```
Version: ^3.4.2
Lightweight JavaScript framework
Usage:
- Form interactions
- Dynamic UI updates
- Client-side validation
- Event handling
```

#### Axios
```
Version: ^1.11.0
HTTP client library
Usage:
- API calls from frontend
- Request/response interceptors
- Error handling
- Token management
```

### Build & Development Tools

#### Vite
```
Version: ^7.0.7
Next-generation build tool
Features:
- Fast development server
- Instant HMR (Hot Module Replacement)
- Optimized production build
- CSS preprocessing
```

#### npm
```
Node Package Manager
Scripts available:
- npm run dev   → Development build with HMR
- npm run build → Production build
- npm install   → Install dependencies
```

#### PostCSS
```
Version: ^8.4.31
CSS transformation tool
Used for:
- Autoprefixer (vendor prefixes)
- Tailwind CSS processing
```

#### Composer
```
PHP Package Manager
Key packages:
- laravel/framework: ^12.0
- laravel/sanctum: ^4.3
- laravel/tinker: ^2.10.1
- Dev: phpunit, faker, pint
```

---

## 🏗️ Laravel Architecture

### MVC Pattern Implementation

```
Layer 1: Controller
├─ Handle HTTP requests
├─ Validate input (Form Requests)
├─ Call services/models
└─ Return responses

Layer 2: Model
├─ Represent database tables
├─ Define relationships
├─ Include business logic
└─ Database queries via Eloquent

Layer 3: Database
├─ Persistent data storage
├─ ACID compliance
├─ Query optimization
└─ Backup & recovery
```

### Dir Structure & Responsibilities

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php         [Authentication logic]
│   │       ├── CompetitionController.php  [Competition CRUD]
│   │       └── RegistrationController.php [Registration management]
│   │
│   ├── Middleware/
│   │   ├── AdminMiddleware.php            [Admin authorization]
│   │   └── Authenticate.php               [Auth middleware]
│   │
│   ├── Requests/                          [Form validation]
│   └── Resources/                         [API responses]
│
├── Models/
│   ├── User.php                           [User model + relationships]
│   ├── Competition.php                    [Competition model]
│   └── Registration.php                   [Registration model]
│
├── Services/                              [Optional: business logic]
│   ├── AuthService.php
│   ├── CompetitionService.php
│   └── RegistrationService.php
│
└── Providers/
    └── AppServiceProvider.php             [Service container]
```

### Request Lifecycle

```
1. HTTP Request
   ↓
2. Kernel processes middleware
   ↓
3. Router matches route
   ↓
4. Controller method called
   ├─ Instantiate FormRequest (validation)
   ├─ Call Model/Service
   ├─ Process data
   └─ Return response
   ↓
5. Response middleware
   ↓
6. JSON/HTML response
   ↓
7. Client receives
```

---

## 🔌 API Documentation

### Detailed Endpoint Specifications

#### Authentication Endpoints

##### 1. POST /api/auth/register
**Purpose:** Register new user account

**Request:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Validation:**
```
- name: required, string, max:255
- email: required, email, unique:users
- password: required, min:8, confirmed
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "peserta",
            "created_at": "2026-02-24T10:00:00Z"
        }
    }
}
```

**Code Example:**
```php
// In AuthController::register()
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'peserta',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'User registered successfully',
        'data' => ['user' => $user],
    ], 201);
}
```

---

##### 2. POST /api/auth/login
**Purpose:** Authenticate user and get token

**Request:**
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Validation:**
```
- email: required, email, exists:users
- password: required
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "role": "admin"
        },
        "token": "1|A2B3C4D5E6F7G8H9I0J1K2L3M4N5O6P7Q8R9S0T1U2V3"
    }
}
```

**Error Response (401 Unauthorized):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

---

##### 3. GET /api/auth/me
**Purpose:** Get current authenticated user info

**Request Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "role": "admin",
            "created_at": "2026-01-01T00:00:00Z"
        }
    }
}
```

---

##### 4. POST /api/auth/logout
**Purpose:** Logout and invalidate token

**Request Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

#### Competition Endpoints

##### 5. GET /api/competitions
**Purpose:** List all competitions (public endpoint)

**Query Parameters:**
```
?page=1&per_page=15&sort=created_at&order=desc
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Competitions retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Lomba Coding 2026",
            "description": "Kompetisi programming tingkat nasional...",
            "poster_path": "/storage/posters/comp1.jpg",
            "created_at": "2026-02-24T10:00:00Z",
            "updated_at": "2026-02-24T10:00:00Z"
        },
        {
            "id": 2,
            "title": "Hackathon 2026",
            "description": "48-hour coding event...",
            "poster_path": "/storage/posters/comp2.jpg",
            "created_at": "2026-02-24T11:00:00Z",
            "updated_at": "2026-02-24T11:00:00Z"
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

**Implementation:**
```php
public function index(Request $request)
{
    $competitions = Competition::paginate(15);
    
    return response()->json([
        'success' => true,
        'message' => 'Competitions retrieved',
        'data' => $competitions->items(),
        'pagination' => [
            'total' => $competitions->total(),
            'per_page' => $competitions->perPage(),
            'current_page' => $competitions->currentPage(),
            'last_page' => $competitions->lastPage(),
        ],
    ]);
}
```

---

##### 6. GET /api/competitions/{id}
**Purpose:** Get single competition detail

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Lomba Coding 2026",
        "description": "Deskripsi panjang...",
        "poster_path": "/storage/posters/comp1.jpg",
        "registration_count": 45,
        "created_at": "2026-02-24T10:00:00Z",
        "updated_at": "2026-02-24T10:00:00Z"
    }
}
```

---

##### 7. POST /api/competitions (Admin Only)
**Purpose:** Create new competition

**Request:**
```
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

{
    "title": "New Competition",
    "description": "Description text",
    "poster": <file>
}
```

**Validation:**
```php
'title' => 'required|string|max:255|unique:competitions',
'description' => 'required|string',
'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Competition created successfully",
    "data": {
        "id": 3,
        "title": "New Competition",
        "description": "Description text",
        "poster_path": "/storage/posters/comp3.jpg",
        "created_at": "2026-02-24T12:00:00Z"
    }
}
```

**Authorization Check:**
```php
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/competitions', [CompetitionController::class, 'store']);
});

// In AdminMiddleware.php
public function handle(Request $request, Closure $next)
{
    if ($request->user()->role !== 'admin') {
        abort(403, 'Unauthorized action');
    }
    
    return $next($request);
}
```

---

##### 8. PUT /api/competitions/{id} (Admin Only)
**Purpose:** Update competition

**Request:**
```json
{
    "title": "Updated Title",
    "description": "Updated description",
    "poster": <file (optional)>
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Competition updated successfully",
    "data": {
        "id": 1,
        "title": "Updated Title",
        ...
    }
}
```

---

##### 9. DELETE /api/competitions/{id} (Admin Only)
**Purpose:** Delete competition

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Competition deleted successfully"
}
```

**Cascade Behavior:**
```sql
-- All registrations for this competition are deleted
DELETE FROM registrations WHERE competition_id = 1;
DELETE FROM competitions WHERE id = 1;
```

---

#### Registration Endpoints

##### 10. POST /api/registrations (Peserta)
**Purpose:** Register to competition

**Request:**
```json
{
    "competition_id": 1
}
```

**Validation:**
```php
'competition_id' => 'required|exists:competitions,id'
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Successfully registered to competition",
    "data": {
        "id": 10,
        "user_id": 2,
        "competition_id": 1,
        "status": "submitted",
        "created_at": "2026-02-24T12:30:00Z"
    }
}
```

**Unique Constraint:**
```php
// Prevent duplicate registration
if (Registration::where('user_id', auth()->id())
    ->where('competition_id', $request->competition_id)
    ->exists()) {
    return response()->json([
        'success' => false,
        'message' => 'Already registered to this competition'
    ], 422);
}
```

---

##### 11. GET /api/registrations (User's Own)
**Purpose:** Get user's registrations

**Response (200 OK):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user_id": 2,
            "competition_id": 1,
            "competition": {
                "id": 1,
                "title": "Lomba Coding",
                "description": "..."
            },
            "status": "submitted",
            "created_at": "2026-02-24T10:00:00Z"
        }
    ]
}
```

**Query with Relationships:**
```php
public function index(Request $request)
{
    $registrations = auth()->user()
        ->registrations()
        ->with('competition')
        ->latest()
        ->paginate();
    
    return response()->json([
        'success' => true,
        'data' => $registrations->items(),
    ]);
}
```

---

##### 12. GET /api/admin/registrations (Admin Only)
**Purpose:** View all registrations

**Response (200 OK):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user": {
                "id": 2,
                "name": "Peserta 1",
                "email": "peserta1@example.com"
            },
            "competition": {
                "id": 1,
                "title": "Lomba Coding"
            },
            "status": "submitted",
            "created_at": "2026-02-24T10:00:00Z"
        }
    ]
}
```

---

### Response Interceptor Pattern

```javascript
// resources/js/bootstrap.js
import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Request interceptor - add token
api.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Response interceptor - handle errors
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            // Handle unauthorized
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default api;
```

---

## 📊 Database Schema Details

### Complete Table Definitions

#### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL COMMENT 'User full name',
    email VARCHAR(255) UNIQUE NOT NULL COMMENT 'Email address (unique)',
    email_verified_at TIMESTAMP NULL COMMENT 'Email verification timestamp',
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    role ENUM('admin', 'peserta') NOT NULL DEFAULT 'peserta' COMMENT 'User role',
    remember_token VARCHAR(100) NULL COMMENT 'Remember me token',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes for performance
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
  COMMENT='User accounts and authentication';
```

**Relationships:**
```php
// In User model
public function registrations()
{
    return $this->hasMany(Registration::class);
}
```

---

#### Competitions Table
```sql
CREATE TABLE competitions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL COMMENT 'Competition title',
    description LONGTEXT NOT NULL COMMENT 'Full description',
    poster_path VARCHAR(255) NULL COMMENT 'Path to poster image',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX idx_created_at (created_at),
    INDEX idx_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Competition/event listings';
```

**Relationships:**
```php
// In Competition model
public function registrations()
{
    return $this->hasMany(Registration::class);
}
```

---

#### Registrations Table
```sql
CREATE TABLE registrations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Reference to users table',
    competition_id BIGINT UNSIGNED NOT NULL COMMENT 'Reference to competitions table',
    status ENUM('submitted') NOT NULL DEFAULT 'submitted' COMMENT 'Registration status',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Primary constraint: prevent duplicate registration
    UNIQUE KEY unique_registration (user_id, competition_id) COMMENT 'One user per competition',
    
    -- Foreign key constraints
    CONSTRAINT fk_registrations_user_id 
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_registrations_competition_id 
        FOREIGN KEY (competition_id) REFERENCES competitions(id) ON DELETE CASCADE,
    
    -- Indexes for queries
    INDEX idx_user_id (user_id),
    INDEX idx_competition_id (competition_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='User registrations to competitions';
```

**Relationships:**
```php
// In Registration model
public function user()
{
    return $this->belongsTo(User::class);
}

public function competition()
{
    return $this->belongsTo(Competition::class);
}
```

---

### Critical Constraints & Integrity

**UNIQUE Constraint on Registrations:**
```sql
UNIQUE KEY unique_registration (user_id, competition_id)
```
**Purpose:** Mencegah satu user mendaftar 2x ke perlombaan yang sama
**Effect:** Database akan reject duplicate registrations dengan DUPLICATE KEY error

**Foreign Key with CASCADE:**
```sql
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
```
**Purpose:** Jika user dihapus, semua registrasi mereka otomatis dihapus
**Effect:** Data integrity terjaga, no orphaned records

---

## 💻 Code Samples

### Model Relationships

#### User Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Relationship: User has many registrations
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    
    // Helper method: Check if admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
```

#### Competition Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'poster_path',
    ];
    
    // Relationship: Competition has many registrations
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    
    // Get registration count
    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }
}
```

#### Registration Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'competition_id',
        'status',
    ];
    
    // Relationship: Registration belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship: Registration belongs to Competition
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
```

---

### Controller Implementation

#### AuthController
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'peserta', // Default role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => ['user' => $user],
        ], 201);
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    // Get current user
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => ['user' => $request->user()],
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
```

#### CompetitionController
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    // Get all competitions (public)
    public function index()
    {
        $competitions = Competition::latest()->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Competitions retrieved',
            'data' => $competitions->items(),
            'pagination' => [
                'total' => $competitions->total(),
                'per_page' => $competitions->perPage(),
                'current_page' => $competitions->currentPage(),
                'last_page' => $competitions->lastPage(),
            ],
        ]);
    }

    // Get single competition (public)
    public function show($id)
    {
        $competition = Competition::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $competition,
        ]);
    }

    // Create competition (admin only)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:competitions',
            'description' => 'required|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $competition = Competition::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'poster_path' => $posterPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Competition created successfully',
            'data' => $competition,
        ], 201);
    }

    // Update competition (admin only)
    public function update(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $competition->update($validated);

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
            $competition->update(['poster_path' => $posterPath]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Competition updated successfully',
            'data' => $competition,
        ]);
    }

    // Delete competition (admin only)
    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->delete();

        return response()->json([
            'success' => true,
            'message' => 'Competition deleted successfully',
        ]);
    }
}
```

#### RegistrationController
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    // Get user's registrations
    public function index(Request $request)
    {
        $registrations = $request->user()
            ->registrations()
            ->with('competition')
            ->latest()
            ->paginate();

        return response()->json([
            'success' => true,
            'data' => $registrations->items(),
            'pagination' => [
                'total' => $registrations->total(),
                'per_page' => $registrations->perPage(),
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
            ],
        ]);
    }

    // Register to competition
    public function store(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);

        // Check if already registered
        $exists = Registration::where('user_id', $request->user()->id)
            ->where('competition_id', $validated['competition_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Already registered to this competition',
            ], 422);
        }

        $registration = Registration::create([
            'user_id' => $request->user()->id,
            'competition_id' => $validated['competition_id'],
            'status' => 'submitted',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered',
            'data' => $registration,
        ], 201);
    }

    // Get registration detail
    public function show($id)
    {
        $registration = Registration::with('user', 'competition')
            ->findOrFail($id);

        // Check authorization
        if ($registration->user_id !== auth()->id() && 
            !auth()->user()->isAdmin()) {
            abort(403);
        }

        return response()->json([
            'success' => true,
            'data' => $registration,
        ]);
    }

    // Cancel registration
    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);

        // Check authorization
        if ($registration->user_id !== auth()->id() && 
            !auth()->user()->isAdmin()) {
            abort(403);
        }

        $registration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registration cancelled',
        ]);
    }

    // Get all registrations (admin only)
    public function adminIndex()
    {
        $registrations = Registration::with('user', 'competition')
            ->latest()
            ->paginate();

        return response()->json([
            'success' => true,
            'data' => $registrations->items(),
            'pagination' => [
                'total' => $registrations->total(),
                'per_page' => $registrations->perPage(),
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
            ],
        ]);
    }
}
```

---

### Middleware Implementation

#### AdminMiddleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Check if user has admin role
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden - Admin access only',
            ], 403);
        }

        return $next($request);
    }
}
```

---

## ⚡ Performance Optimization

### Database Query Optimization

#### N+1 Query Problem (Bad)
```php
// This causes multiple queries!
$registrations = Registration::all();
foreach ($registrations as $reg) {
    echo $reg->user->name; // Extra query per iteration
}
```

#### Eager Loading (Good)
```php
// Load all relationships at once
$registrations = Registration::with('user', 'competition')->get();
foreach ($registrations as $reg) {
    echo $reg->user->name; // No extra query!
}
```

### Indexing Strategy

```sql
-- Indexes for faster lookups
INDEX idx_email (email)              -- Search by email
INDEX idx_role (role)                 -- Filter by role
INDEX idx_user_id (user_id)           -- Find registrations by user
INDEX idx_competition_id (competition_id) -- Find registrations by comp
INDEX unique_registration (user_id, competition_id) -- Prevent duplicates
```

### Caching Strategies

```php
// Cache competition list (1 hour)
$competitions = Cache::remember('competitions', 3600, function () {
    return Competition::latest()->get();
});

// Cache user registrations (5 minutes)
$registrations = Cache::remember(
    'user.' . auth()->id() . '.registrations',
    300,
    function () {
        return auth()->user()->registrations()->with('competition')->get();
    }
);

// Clear cache on create/update
Competition::creating(function ($competition) {
    Cache::forget('competitions');
});
```

---

## 🔒 Security Implementation

### Password Security
```php
// Hash password with bcrypt
$user->password = Hash::make($request->password);

// Verify password
if (!Hash::check($request->password, $user->password)) {
    abort(401);
}
```

### Token Authentication (Sanctum)
```php
// Generate token
$token = $user->createToken('auth-token')->plainTextToken;

// Revoke tokens
$user->tokens()->delete();

// In middleware
Route::middleware(['auth:sanctum'])->group(function () {
    // protected routes
});
```

### Input Validation
```php
$request->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8',
    'competition_id' => 'required|exists:competitions,id',
]);
```

### CORS & HTTPS
```php
// config/cors.php
'allowed_origins' => ['http://localhost:3000'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['Content-Type', 'Authorization'],
```

---

## 🧪 Testing Strategy

### Feature Test Example
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Competition;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function test_peserta_can_register_to_competition()
    {
        // Setup
        $peserta = User::factory()->create(['role' => 'peserta']);
        $competition = Competition::factory()->create();

        // Action
        $response = $this->actingAs($peserta)
            ->postJson('/api/registrations', [
                'competition_id' => $competition->id,
            ]);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Successfully registered',
            ]);

        $this->assertDatabaseHas('registrations', [
            'user_id' => $peserta->id,
            'competition_id' => $competition->id,
        ]);
    }

    public function test_duplicate_registration_prevented()
    {
        // Already registered
        $registration = Registration::factory()->create();

        // Try to register again
        $response = $this->actingAs($registration->user)
            ->postJson('/api/registrations', [
                'competition_id' => $registration->competition_id,
            ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }
}
```

### Unit Test Example
```php
<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_can_have_registrations()
    {
        $user = User::factory()->hasRegistrations(3)->create();

        $this->assertCount(3, $user->registrations);
    }

    public function test_admin_user_has_admin_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->isAdmin());
    }
}
```

---

**Last Updated:** February 24, 2026  
**Status:** Complete & Production Ready ✅
