# Phase 3: Implementation Plan (SDLC)

## 📋 Implementation Roadmap

### Sprint 1: Foundation (Day 1-2)
- [ ] Clean up project structure
- [ ] Update models (User, Competition, Registration)
- [ ] Create migrations (simplified schema)
- [ ] Setup API routes and controllers

### Sprint 2: Core Features (Day 3-4)
- [ ] Authentication system (Login, Register, Logout)
- [ ] Competition CRUD operations
- [ ] Registration management
- [ ] Admin authorization

### Sprint 3: Testing & Polish (Day 5)
- [ ] Unit tests
- [ ] Feature tests
- [ ] Bug fixes
- [ ] Documentation

---

## 🛠️ Implementation Tasks

### Task 1: Database Setup ✅ IN PROGRESS
**Description**: Create clean migration files for simplified schema
**Owner**: Developer
**Duration**: 2 hours
**Sub-tasks**:
- [ ] Delete old migrations (DocumentTemplate, old SubmissionDocument)
- [ ] Update users migration (add role column)
- [ ] Create competitions migration
- [ ] Create registrations migration
- [ ] Create seeder for test data

### Task 2: Model Implementation
**Description**: Create/Update Eloquent models
**Owner**: Developer
**Duration**: 1.5 hours
**Sub-tasks**:
- [ ] Update User model
- [ ] Create Competition model
- [ ] Create Registration model
- [ ] Setup relationships (hasMany, belongsTo)

### Task 3: API Controllers
**Description**: Create API controllers
**Owner**: Developer
**Duration**: 3 hours
**Sub-tasks**:
- [ ] Create AuthController
- [ ] Create CompetitionController
- [ ] Create RegistrationController
- [ ] Implement all endpoints

### Task 4: Routes Setup
**Description**: Configure API routes
**Owner**: Developer
**Duration**: 1 hour
**Sub-tasks**:
- [ ] Create routes/api.php
- [ ] Setup route groups
- [ ] Add middleware
- [ ] Test routes

### Task 5: Authentication & Authorization
**Description**: Implement Sanctum authentication
**Owner**: Developer
**Duration**: 2 hours
**Sub-tasks**:
- [ ] Setup Sanctum
- [ ] Create AuthService
- [ ] Implement AdminMiddleware
- [ ] Test auth flow

### Task 6: File Upload
**Description**: Implement poster upload
**Owner**: Developer
**Duration**: 1.5 hours
**Sub-tasks**:
- [ ] Setup file storage
- [ ] Create upload handler
- [ ] Add validation
- [ ] Test uploads

### Task 7: Testing
**Description**: Write comprehensive tests
**Owner**: QA/Developer
**Duration**: 3 hours
**Sub-tasks**:
- [ ] Auth feature tests
- [ ] Competition CRUD tests
- [ ] Registration tests
- [ ] Authorization tests

### Task 8: Documentation
**Description**: Document code and API
**Owner**: Developer
**Duration**: 2 hours
**Sub-tasks**:
- [ ] Update README.md
- [ ] Document API endpoints
- [ ] Add code comments
- [ ] Create deployment guide

---

## 🗂️ File Structure After Implementation

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php (NEW)
│   │   │   ├── CompetitionController.php (NEW)
│   │   │   └── RegistrationController.php (NEW)
│   │   └── Controller.php
│   └── Middleware/
│       ├── AdminMiddleware.php (NEW)
│       └── Authenticate.php
├── Models/
│   ├── User.php (UPDATED)
│   ├── Competition.php (NEW)
│   ├── Registration.php (NEW)
│   └── DocumentTemplate.php (DELETED)
└── Services/
    ├── AuthService.php (NEW)
    ├── CompetitionService.php (NEW)
    └── RegistrationService.php (NEW)

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php (UPDATED)
│   ├── 2025_11_30_053130_create_competitions_table.php (UPDATED)
│   ├── 2025_11_30_061524_create_registrations_table.php (UPDATED)
│   ├── [DELETE] 2025_11_30_061400_create_document_templates_table.php
│   ├── [DELETE] 2025_11_30_061622_create_submission_documents_table.php
│   └── [DELETE] Old migrations
└── seeders/
    └── DatabaseSeeder.php (UPDATED)

routes/
├── api.php (NEW)
└── web.php (SIMPLIFIED)

tests/
├── Feature/
│   ├── AuthTest.php (NEW)
│   ├── CompetitionTest.php (NEW)
│   └── RegistrationTest.php (NEW)
└── Unit/
    ├── AuthServiceTest.php (NEW)
    └── ...

docs/sdlc/
├── 01-REQUIREMENTS.md ✅
├── 02-DESIGN.md ✅
├── 03-IMPLEMENTATION_PLAN.md (THIS FILE)
├── 04-TESTING.md (NEXT)
└── 05-DEPLOYMENT.md (NEXT)
```

---

## 📝 Code Standards

### Naming Conventions
```
Models: PascalCase (User, Competition, Registration)
Methods: camelCase (getCompetitionById, createRegistration)
Variables: snake_case ($user_id, $competition_id)
Constants: UPPER_SNAKE_CASE (STATUS_SUBMITTED)
Routes: kebab-case (/api/v1/competitions, /api/v1/registrations)
```

### Code Style
```
- PSR-12 standards
- 4-space indentation
- Max line length: 120 characters
- Use type hints on methods
- Use strict types
```

### Documentation Comments
```php
/**
 * Get all competitions with pagination
 *
 * @param int $page
 * @param int $perPage
 * @return \Illuminate\Pagination\Paginator
 */
public function getCompetitions(int $page = 1, int $perPage = 15)
{
    // implementation
}
```

---

## 🔄 Configuration Changes

### config/sanctum.php
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
'expiration' => null,  // Token doesn't expire
```

### config/cors.php
```php
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

### config/filesystems.php
```php
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
    ],
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

---

## 🧪 Testing Approach

### Test Structure
```
Feature Tests:
- AuthTest.php (login, register, logout)
- CompetitionTest.php (CRUD operations)
- RegistrationTest.php (register, cancel)

Unit Tests:
- AuthService test
- Validation tests
- Relationship tests
```

### Sample Test Cases
```php
// Feature Test Example
test('user can login with valid credentials', function () {
    $user = User::factory()->create(['password' => 'password']);
    
    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);
    
    $response->assertStatus(200)
        ->assertJsonStructure(['data' => ['token']]);
});

// Feature Test Example
test('admin can create competition', function () {
    $admin = User::factory()->admin()->create();
    
    $response = $this->actingAs($admin)
        ->postJson('/api/competitions', [
            'title' => 'Test Competition',
            'description' => 'Test Description',
        ]);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('competitions', ['title' => 'Test Competition']);
});
```

---

## 📊 Progress Tracking

### Completion Checklist

#### Database & Models
- [ ] Migrations created
- [ ] Models setup
- [ ] Relationships defined
- [ ] Seeders created

#### API Controllers
- [ ] AuthController
- [ ] CompetitionController
- [ ] RegistrationController
- [ ] Error handling

#### Routes
- [ ] Auth routes
- [ ] Competition routes
- [ ] Registration routes
- [ ] Admin routes

#### Authentication
- [ ] Sanctum setup
- [ ] Auth middleware
- [ ] Admin middleware
- [ ] Token management

#### Features
- [ ] User registration
- [ ] User login
- [ ] Competition listing
- [ ] Competition creation
- [ ] Registration system
- [ ] File upload

#### Testing
- [ ] Unit tests (80%+)
- [ ] Feature tests (80%+)
- [ ] Integration tests
- [ ] Security tests

#### Documentation
- [ ] Code comments
- [ ] API documentation
- [ ] Setup guide
- [ ] Architecture docs

---

## 🚀 Deployment Preparation

### Pre-Deployment Checklist
- [ ] All tests passing
- [ ] Code review completed
- [ ] Security audit passed
- [ ] Performance tested
- [ ] Documentation complete
- [ ] Database backup strategy ready
- [ ] Rollback plan documented

### Environment Configuration
```
.env variables needed:
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_DATABASE=tugas_akhir
- DB_USERNAME=root
- DB_PASSWORD=
- APP_URL=http://localhost:8000
- SANCTUM_STATEFUL_DOMAINS=localhost
```

---

## 📝 Notes & Considerations

- Keep changes backward compatible where possible
- Document all breaking changes
- Use feature flags for gradual rollout
- Monitor performance metrics
- Plan rollback strategy
- Communication with stakeholders

---

## 📌 Sign-Off

- **Date**: 2026-02-19
- **Status**: Ready for Implementation
