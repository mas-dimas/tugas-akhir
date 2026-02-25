# 📊 DIAGRAM PROYEK & VISUALISASI
## Sistem Pendaftaran Perlombaan

---

## 1. Diagram Alur Sistem Keseluruhan

```mermaid
graph TB
    subgraph Client["🌐 CLIENT LAYER"]
        Browser["Web Browser"]
        Mobile["📱 Mobile App"]
    end
    
    subgraph API["🔌 API GATEWAY"]
        Routes["REST API Routes<br/>12 Endpoints"]
    end
    
    subgraph App["⚙️ APPLICATION LAYER"]
        Auth["AuthController<br/>Login/Register"]
        Comp["CompetitionController<br/>CRUD"]
        Reg["RegistrationController<br/>Register/View"]
        Middleware["Middleware<br/>Auth/Admin"]
    end
    
    subgraph Models["📊 MODELS"]
        UserModel["User Model"]
        CompModel["Competition Model"]
        RegModel["Registration Model"]
    end
    
    subgraph DB["🗄️ DATABASE"]
        UsersTable["Users Table"]
        CompTable["Competitions Table"]
        RegTable["Registrations Table"]
    end
    
    Browser -->|HTTP/JSON| Routes
    Mobile -->|HTTP/JSON| Routes
    Routes -->|Route to| Auth
    Routes -->|Route to| Comp
    Routes -->|Route to| Reg
    
    Auth -->|Check| Middleware
    Comp -->|Check| Middleware
    Reg -->|Check| Middleware
    
    Auth -->|Query/Save| UserModel
    Comp -->|Query/Save| CompModel
    Reg -->|Query/Save| RegModel
    
    UserModel -->|ORM| UsersTable
    CompModel -->|ORM| CompTable
    RegModel -->|ORM| RegTable
    
    UsersTable -.->|FK| RegTable
    CompTable -.->|FK| RegTable
```

---

## 2. Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ REGISTRATIONS : creates
    COMPETITIONS ||--o{ REGISTRATIONS : has
    
    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role
        timestamp created_at
        timestamp updated_at
    }
    
    COMPETITIONS {
        bigint id PK
        string title
        text description
        string poster_path
        timestamp created_at
        timestamp updated_at
    }
    
    REGISTRATIONS {
        bigint id PK
        bigint user_id FK
        bigint competition_id FK
        enum status
        timestamp created_at
        timestamp updated_at
    }
```

---

## 3. API Request/Response Flow

```mermaid
sequenceDiagram
    participant Client as Client App
    participant API as API Server
    participant DB as Database
    
    Client->>API: 1. POST /auth/login
    Note over API: Validate credentials
    API->>DB: Query user
    DB-->>API: Return user
    Note over API: Generate token
    API-->>Client: 2. Return token
    
    Client->>API: 3. GET /competitions
    Note over API: No auth needed
    API->>DB: Query all competitions
    DB-->>API: Return competitions
    API-->>Client: 4. Return list
    
    Client->>API: 5. POST /registrations
    Note over API: With Bearer Token
    Note over API: Auth check
    Note over API: Validate input
    API->>DB: Create registration
    DB-->>API: Confirm
    API-->>Client: 6. Return 201 Created
    
    Client->>API: 7. GET /registrations
    Note over API: With Bearer Token
    API->>DB: Query registrations
    DB-->>API: Return data
    API-->>Client: 8. Return list
```

---

## 4. Admin Features Flow

```mermaid
graph LR
    Admin["👨 ADMIN"]
    
    Login["🔐 Login"]
    Dashboard["📊 Dashboard"]
    
    CompFeatures["Competition Features"]
    Create["➕ Create"]
    Read["👁️ View"]
    Update["✏️ Edit"]
    Delete["❌ Delete"]
    Upload["📤 Upload"]
    
    RegFeatures["Registration Features"]
    ViewAll["👀 View All"]
    ViewDetail["🔍 View Detail"]
    Remove["🗑️ Remove"]
    
    Admin -->|Step 1| Login
    Login -->|Step 2| Dashboard
    
    Dashboard -->|Feature 1| CompFeatures
    Dashboard -->|Feature 2| RegFeatures
    
    CompFeatures --> Create
    CompFeatures --> Read
    CompFeatures --> Update
    CompFeatures --> Delete
    CompFeatures --> Upload
    
    RegFeatures --> ViewAll
    RegFeatures --> ViewDetail
    RegFeatures --> Remove
    
    style Admin fill:#3498db
    style Dashboard fill:#2ecc71
    style CompFeatures fill:#e74c3c
    style RegFeatures fill:#f39c12
```

---

## 5. Peserta Features Flow

```mermaid
graph LR
    Peserta["👤 PESERTA"]
    
    Choice{"New User?"}
    Register["📝 Register"]
    Login["🔐 Login"]
    
    Browse["📚 Browse"]
    Detail["🔍 Details"]
    Daftar["✅ Register"]
    
    Track["📍 Track"]
    Status["📊 Status"]
    Cancel["❌ Cancel"]
    
    Peserta -->|Step 1| Choice
    Choice -->|Yes| Register
    Choice -->|No| Login
    
    Register -->|Step 2| Login
    Login -->|Step 3| Browse
    
    Browse -->|View All| Browse
    Browse -->|Click| Detail
    Detail -->|Step 4| Daftar
    
    Login -->|Step 5| Track
    Track -->|Check| Status
    Track -->|Option| Cancel
    
    style Peserta fill:#3498db
    style Browse fill:#2ecc71
    style Daftar fill:#f39c12
    style Status fill:#9b59b6
```

---

## 6. Database Relationships

```mermaid
graph TB
    subgraph UserData["👤 USER DATA"]
        Users["USERS Table<br/>id, name, email, password, role<br/>created_at, updated_at"]
    end
    
    subgraph CompData["🎯 COMPETITION DATA"]
        Comps["COMPETITIONS Table<br/>id, title, description, poster_path<br/>created_at, updated_at"]
    end
    
    subgraph RegData["📋 REGISTRATION DATA"]
        Regs["REGISTRATIONS Table<br/>id, user_id(FK), competition_id(FK)<br/>status, created_at, updated_at<br/>UNIQUE(user_id, competition_id)"]
    end
    
    Users -->|1:N<br/>hasMany| Regs
    Comps -->|1:N<br/>hasMany| Regs
    Regs -->|N:1<br/>belongsTo| Users
    Regs -->|N:1<br/>belongsTo| Comps
    
    style Users fill:#3498db,stroke:#2c3e50,stroke-width:2px
    style Comps fill:#2ecc71,stroke:#2c3e50,stroke-width:2px
    style Regs fill:#e74c3c,stroke:#2c3e50,stroke-width:2px
```

---

## 7. Authentication & Authorization Flow

```mermaid
graph TB
    subgraph Public["🌐 PUBLIC"]
        PubRead["GET /competitions<br/>GET /competitions/{id}"]
        AuthEndpoint["POST /auth/login<br/>POST /auth/register"]
    end
    
    subgraph Protected["🔐 PROTECTED - Any Authenticated User"]
        UserEP["GET /auth/me<br/>POST /auth/logout<br/>GET /registrations<br/>POST /registrations<br/>DELETE /registrations/{id}"]
    end
    
    subgraph AdminOnly["👑 ADMIN ONLY"]
        AdminEP["POST /competitions<br/>PUT /competitions/{id}<br/>DELETE /competitions/{id}<br/>GET /admin/registrations"]
    end
    
    Request["HTTP Request"]
    
    Request -->|No Auth| PubRead
    Request -->|No Auth| AuthEndpoint
    
    Request -->|Auth Token| Public_Check{"Check Token"}
    Public_Check -->|Valid| Protected
    
    Request -->|Auth Token + Admin| Admin_Check{"Admin Check"}
    Admin_Check -->|Valid + Admin| AdminOnly
    Admin_Check -->|Valid + Not Admin| Forbidden["❌ 403 Forbidden"]
    Public_Check -->|Invalid| Unauthorized["❌ 401 Unauthorized"]
    
    style Public fill:#95a5a6,stroke:#2c3e50,stroke-width:2px
    style Protected fill:#3498db,stroke:#2c3e50,stroke-width:2px
    style AdminOnly fill:#e74c3c,stroke:#2c3e50,stroke-width:2px
    style Forbidden fill:#c0392b,color:#fff
    style Unauthorized fill:#c0392b,color:#fff
```

---

## 8. API Endpoints Overview

```mermaid
graph LR
    subgraph Auth["🔐 Authentication"]
        L["POST /login"]
        R["POST /register"]
        M["GET /me"]
        LO["POST /logout"]
    end
    
    subgraph Public["🌐 Public Competitions"]
        LC["GET /competitions"]
        SC["GET /competitions/{id}"]
    end
    
    subgraph UserReg["👤 User Registrations"]
        GR["GET /registrations"]
        CR["POST /registrations"]
        SR["GET /registrations/{id}"]
        DR["DELETE /registrations/{id}"]
    end
    
    subgraph Admin["👑 Admin Only"]
        PC["POST /competitions"]
        UC["PUT /competitions/{id}"]
        DC["DELETE /competitions/{id}"]
        AR["GET /admin/registrations"]
    end
    
    style Auth fill:#3498db,stroke:#2c3e50,stroke-width:2px
    style Public fill:#2ecc71,stroke:#2c3e50,stroke-width:2px
    style UserReg fill:#f39c12,stroke:#2c3e50,stroke-width:2px
    style Admin fill:#e74c3c,stroke:#2c3e50,stroke-width:2px
```

---

## 9. Project Structure Tree

```mermaid
graph TB
    ROOT["📁 tugas-akhir"]
    
    Backend["📦 Backend"]
    Frontend["📦 Frontend"]
    Mobile["📦 Mobile"]
    Docs["📚 Documentation"]
    Config["⚙️ Configuration"]
    
    ROOT --> Backend
    ROOT --> Frontend
    ROOT --> Mobile
    ROOT --> Docs
    ROOT --> Config
    
    Backend --> App["app/"]
    Backend --> Database["database/"]
    Backend --> Routes["routes/"]
    Backend --> Tests["tests/"]
    
    App --> Http["Http/"]
    App --> Models["Models/"]
    App --> Providers["Providers/"]
    
    Http --> Controllers["Controllers/Api/"]
    Http --> Middleware["Middleware/"]
    
    Controllers --> Auth["AuthController"]
    Controllers --> Comp["CompetitionController"]
    Controllers --> Reg["RegistrationController"]
    
    Models --> User["User.php"]
    Models --> Competition["Competition.php"]
    Models --> Registration["Registration.php"]
    
    Database --> Migrations["migrations/"]
    Database --> Seeders["seeders/"]
    Database --> Factories["factories/"]
    
    Routes --> Api_Routes["api.php<br/>12 endpoints"]
    Routes --> Web_Routes["web.php"]
    
    Frontend --> Resources["resources/"]
    Frontend --> Build["vite, tailwind"]
    
    Resources --> Views["views/"]
    Resources --> CSS["css/"]
    Resources --> JS["js/"]
    
    Mobile --> FlutterApp["Flutter App"]
    
    Docs --> SDLC["docs/sdlc/"]
    
    SDLC --> Req["01-REQUIREMENTS"]
    SDLC --> Design["02-DESIGN"]
    SDLC --> Impl["03-IMPLEMENTATION"]
    
    Config --> EnvFile[".env"]
    Config --> Composer["composer.json"]
    Config --> Package["package.json"]
```

---

## 10. Data Flow: Creating a Competition

```mermaid
sequenceDiagram
    participant Admin as Admin User
    participant Browser as Web Browser
    participant API as API Server
    participant Controller as CompetitionController
    participant Model as Competition Model
    participant DB as MySQL Database
    
    Admin->>Browser: 1. Fill form<br/> (title, desc, poster)
    Browser->>API: 2. POST /competitions<br/> Header: Auth token
    
    API->>Controller: 3. Route to store()
    Note over API: Check middleware<br/>auth:sanctum, admin
    
    Controller->>Controller: 4. Validate input
    Note over Controller: Check title, description<br/>Store poster file
    
    Controller->>Model: 5. Create competition
    Note over Controller: Competition::create(data)
    
    Model->>DB: 6. INSERT INTO competitions
    Note over DB: INSERT query
    
    DB-->>Model: 7. Return created record
    Note over DB: id, title, desc, etc
    
    Model-->>Controller: 8. Return model instance
    
    Controller-->>API: 9. Format response
    Note over Controller: {success, data, message}
    
    API-->>Browser: 10. 201 Created
    Note over API: JSON response
    
    Browser-->>Admin: 11. Show success message
    Admin->>Browser: 12. Refresh, see new competition
```

---

## 11. Data Flow: Peserta Registering

```mermaid
sequenceDiagram
    participant Peserta as Peserta User
    participant Browser as Web Browser
    participant API as API Server
    participant Middleware as Middleware
    participant Controller as RegistrationController
    participant Model as Registration Model
    participant DB as MySQL Database
    
    Peserta->>Browser: 1. Click "Daftar" button
    Note over Peserta: After viewing competition
    
    Browser->>API: 2. POST /registrations<br/> {competition_id: 1}<br/> Header: Auth bearer token
    
    API->>Middleware: 3. Check auth:sanctum
    Middleware->>DB: Verify token
    DB-->>Middleware: ✓ Token valid
    
    Middleware-->>Controller: 4. Proceed
    
    Controller->>Controller: 5. Validate
    Note over Controller: competition_id exists?<br/>user not already registered?
    
    Controller->>Model: 6. Create registration
    Note over Controller: Registration::create({<br/>user_id: auth()->id(),<br/>competition_id: 1<br/>})
    
    Model->>DB: 7. INSERT INTO registrations
    Note over DB: Check UNIQUE constraint<br/>(user_id, competition_id)
    
    DB-->>Model: 8. Registration created
    
    Model-->>Controller: 9. Return record
    
    Controller-->>API: 10. 201 Created
    Note over API: {success, data}
    
    API-->>Browser: 11. Return JSON
    
    Browser-->>Peserta: 12. Show confirmation
    Note over Peserta: "Successfully registered!"
    
    Peserta->>Browser: 13. View my registrations
    Browser->>API: 14. GET /registrations
    API-->>Browser: 15. Return peserta's regs
    Browser-->>Peserta: 16. Show list
```

---

## 12. SDLC Process Phases

```mermaid
graph LR
    Phase1["📋 Phase 1: Requirements"]
    Phase2["🎨 Phase 2: Design"]
    Phase3["💻 Phase 3: Implementation"]
    Phase4["🧪 Phase 4: Testing"]
    Phase5["📦 Phase 5: Deployment"]
    
    Phase1 -->|Deliverables:<br/>User stories, Requirements| Phase2
    Phase2 -->|Deliverables:<br/>Diagrams, Database design| Phase3
    Phase3 -->|Deliverables:<br/>Code, Controllers, Models| Phase4
    Phase4 -->|Deliverables:<br/>Test cases, Bug fixes| Phase5
    Phase5 -->|Deliverables:<br/>Production ready| Finish["✅ Complete"]
    
    style Phase1 fill:#9b59b6,color:#fff,stroke:#6c3483
    style Phase2 fill:#3498db,color:#fff,stroke:#2980b9
    style Phase3 fill:#2ecc71,color:#fff,stroke:#27ae60
    style Phase4 fill:#f39c12,color:#fff,stroke:#d68910
    style Phase5 fill:#e74c3c,color:#fff,stroke:#c0392b
    style Finish fill:#16a085,color:#fff,stroke:#0e6251
```

---

## 13. User Role Matrix

```mermaid
graph TB
    User as User

    User -->|Login| Auth["🔐 Authentication"]
    
    Auth -->|role: admin| Admin["👑 ADMIN"]
    Auth -->|role: peserta| Peserta["👤 PESERTA"]
    
    Admin -->|Can| AC1["✅ Create Competition"]
    Admin -->|Can| AC2["✅ Edit Competition"]
    Admin -->|Can| AC3["✅ Delete Competition"]
    Admin -->|Can| AC4["✅ View All Registrations"]
    Admin -->|Cannot| AC5["❌ Register as Peserta"]
    
    Peserta -->|Can| PC1["✅ Browse Competitions"]
    Peserta -->|Can| PC2["✅ Register to Competition"]
    Peserta -->|Can| PC3["✅ View Own Registrations"]
    Peserta -->|Can| PC4["✅ Cancel Registration"]
    Peserta -->|Cannot| PC5["❌ Create Competitions"]
    Peserta -->|Cannot| PC6["❌ View Others' Registrations"]
    
    style Admin fill:#e74c3c,color:#fff
    style Peserta fill:#3498db,color:#fff
    style Auth fill:#2ecc71,color:#fff
```

---

## 14. Response Format Structure

```mermaid
graph TB
    subgraph Success["✅ Success Response 2xx"]
        SR["JSON Response<br/>{<br/>  'success': true,<br/>  'message': '...',<br/>  'data': {...},<br/>  'pagination': {...}<br/>}"]
    end
    
    subgraph Error["❌ Error Response 4xx/5xx"]
        ER["JSON Response<br/>{<br/>  'success': false,<br/>  'message': '...',<br/>  'errors': {...}<br/>}"]
    end
    
    API["API Response"] -->|Successful| Success
    API -->|Failed| Error
    
    style Success fill:#2ecc71,stroke:#27ae60,stroke-width:2px
    style Error fill:#e74c3c,stroke:#c0392b,stroke-width:2px
```

---

## 15. Deployment Architecture

```mermaid
graph TB
    subgraph Client["🌐 Client Layer"]
        Web["Web Browser"]
        Mobile["Mobile App"]
    end
    
    subgraph Edge["⚡ Edge Layer"]
        CDN["CDN<br/>Static Assets"]
        LB["Load Balancer"]
    end
    
    subgraph App["🖥️ Application Layer"]
        Server1["Laravel Server 1"]
        Server2["Laravel Server 2"]
        Server3["Laravel Server 3"]
    end
    
    subgraph Data["💾 Data Layer"]
        MySQL["MySQL Database<br/>Primary"]
        Replica["MySQL Replica<br/>Backup"]
        Storage["File Storage<br/>Posters/Assets"]
    end
    
    subgraph Monitor["📊 Monitoring"]
        Logs["Logs & Monitoring"]
        Metrics["Metrics & Analytics"]
    end
    
    Web -->|HTTPS| CDN
    Web -->|HTTPS| LB
    Mobile -->|HTTPS| LB
    
    CDN --> LB
    LB -->|Route traffic| Server1
    LB -->|Route traffic| Server2
    LB -->|Route traffic| Server3
    
    Server1 -->|Query| MySQL
    Server2 -->|Query| MySQL
    Server3 -->|Query| MySQL
    
    MySQL -->|Replicate| Replica
    MySQL -->|Read| Storage
    
    Server1 -->|Logs| Logs
    Server2 -->|Logs| Logs
    Server3 -->|Logs| Logs
    
    Server1 -->|Metrics| Metrics
    Server2 -->|Metrics| Metrics
    Server3 -->|Metrics| Metrics
    
    style Client fill:#3498db,stroke:#2c3e50,stroke-width:2px
    style Edge fill:#9b59b6,stroke:#2c3e50,stroke-width:2px
    style App fill:#2ecc71,stroke:#2c3e50,stroke-width:2px
    style Data fill:#e74c3c,stroke:#2c3e50,stroke-width:2px
    style Monitor fill:#f39c12,stroke:#2c3e50,stroke-width:2px
```

---

**Last Updated:** February 24, 2026  
**Status:** Complete ✅  
**All Diagrams:** Ready for Visualization
