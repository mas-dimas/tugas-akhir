# 📊 Diagram Alur Project Sistem Kompetisi

## 1. Arsitektur Sistem Keseluruhan

```mermaid
graph TB
    subgraph "Frontend - Flutter Mobile App"
        A[Flutter App] --> B[Auth Provider]
        A --> C[Competition Provider]
        A --> D[Screens/UI]
    end
    
    subgraph "Backend - Laravel API"
        E[Laravel API] --> F[Controllers]
        F --> G[Models]
        G --> H[(MySQL Database)]
        E --> I[Middleware Auth & Role]
    end
    
    subgraph "Storage"
        J[File Storage] --> K[Posters]
        J --> L[Guidebooks]
        J --> M[Submission Docs]
    end
    
    A -->|HTTP/REST API| E
    F -->|Upload/Download| J
```

## 2. Database Schema & Relasi

```mermaid
erDiagram
    USERS ||--o{ REGISTRATIONS : "mendaftar"
    COMPETITIONS ||--o{ REGISTRATIONS : "memiliki"
    COMPETITIONS ||--o{ DOCUMENT_TEMPLATES : "memiliki"
    REGISTRATIONS ||--o{ SUBMISSION_DOCUMENTS : "mengumpulkan"
    DOCUMENT_TEMPLATES ||--o{ SUBMISSION_DOCUMENTS : "berdasarkan"
    
    USERS {
        bigint id PK
        string name
        string email
        string password
        enum role "admin/peserta"
        timestamp created_at
    }
    
    COMPETITIONS {
        bigint id PK
        string title
        text description
        json stages
        string poster_path
        string guidebook_path
        string source_link
        timestamp created_at
    }
    
    REGISTRATIONS {
        bigint id PK
        bigint user_id FK
        bigint competition_id FK
        enum status "pending/approved/rejected"
        timestamp created_at
    }
    
    DOCUMENT_TEMPLATES {
        bigint id PK
        bigint competition_id FK
        string name
        text description
        boolean required
        int stage_number
    }
    
    SUBMISSION_DOCUMENTS {
        bigint id PK
        bigint registration_id FK
        bigint document_template_id FK
        string file_path
        enum status "pending/approved/rejected"
        text admin_notes
        timestamp created_at
    }
```

## 3. Alur Pengguna (User Flow)

### 3.1 Flow untuk Admin

```mermaid
flowchart TD
    A[Login sebagai Admin] --> B{Autentikasi Berhasil?}
    B -->|Ya| C[Dashboard Admin]
    B -->|Tidak| A
    
    C --> D[Manajemen Kompetisi]
    D --> D1[Buat Kompetisi Baru]
    D --> D2[Edit Kompetisi]
    D --> D3[Hapus Kompetisi]
    D --> D4[Upload Poster & Guidebook]
    
    C --> E[Manajemen Template Dokumen]
    E --> E1[Buat Template Dokumen]
    E --> E2[Set Stage Number]
    E --> E3[Set Required/Optional]
    
    C --> F[Review Pendaftaran]
    F --> F1[Lihat Daftar Peserta]
    F1 --> F2[Lihat Detail Pendaftaran]
    F2 --> F3[Review Dokumen]
    F3 --> F4{Status Dokumen}
    F4 -->|Approve| F5[Set Status: Approved]
    F4 -->|Reject| F6[Set Status: Rejected + Notes]
    F4 -->|Pending| F7[Tunggu Upload Peserta]
```

### 3.2 Flow untuk Peserta

```mermaid
flowchart TD
    A[Login sebagai Peserta] --> B{Autentikasi Berhasil?}
    B -->|Ya| C[Dashboard Peserta]
    B -->|Tidak| A
    
    C --> D[Browse Kompetisi]
    D --> E[Lihat Detail Kompetisi]
    E --> E1[Lihat Poster]
    E --> E2[Download Guidebook]
    E --> E3[Lihat Stages]
    E --> E4[Lihat Template Dokumen]
    
    E --> F{Daftar Kompetisi?}
    F -->|Ya| G[Buat Registration]
    F -->|Tidak| D
    
    G --> H[Registrasi Tersimpan]
    H --> I[Lihat My Registrations]
    
    I --> J[Pilih Registration]
    J --> K[Lihat Status]
    K --> L[Upload Dokumen]
    L --> L1[Pilih Template Dokumen]
    L1 --> L2[Upload File]
    L2 --> L3[Submit]
    
    L3 --> M{Review Admin}
    M -->|Approved| N[Lanjut ke Stage Berikutnya]
    M -->|Rejected| O[Perbaiki & Upload Ulang]
    M -->|Pending| P[Tunggu Review]
```

## 4. API Flow (Laravel Backend)

```mermaid
sequenceDiagram
    participant F as Flutter App
    participant A as Laravel API
    participant M as Middleware
    participant C as Controller
    participant Mod as Model
    participant DB as Database
    participant S as Storage
    
    Note over F,A: Authentication Flow
    F->>A: POST /login (email, password)
    A->>M: Check Credentials
    M->>DB: Query User
    DB-->>M: User Data
    M-->>A: JWT/Session Token
    A-->>F: Token + User Info
    
    Note over F,A: Get Competitions
    F->>A: GET /api/competitions
    A->>M: Verify Token & Role
    M->>C: CompetitionController@index
    C->>Mod: Competition::all()
    Mod->>DB: SELECT * FROM competitions
    DB-->>Mod: Competition Data
    Mod-->>C: Collection
    C-->>A: JSON Response
    A-->>F: List of Competitions
    
    Note over F,A: Register Competition
    F->>A: POST /api/competitions/{id}/register
    A->>M: Verify Token (peserta)
    M->>C: RegistrationController@store
    C->>Mod: Registration::create()
    Mod->>DB: INSERT INTO registrations
    DB-->>Mod: Success
    Mod-->>C: Registration Model
    C-->>A: JSON Success
    A-->>F: Registration Created
    
    Note over F,A: Upload Document
    F->>A: POST /api/registrations/{id}/upload (file)
    A->>M: Verify Token (peserta)
    M->>C: RegistrationController@uploadDocuments
    C->>S: Store File
    S-->>C: File Path
    C->>Mod: SubmissionDocument::create()
    Mod->>DB: INSERT INTO submission_documents
    DB-->>Mod: Success
    Mod-->>C: Document Model
    C-->>A: JSON Success
    A-->>F: Document Uploaded
    
    Note over F,A: Admin Review
    F->>A: POST /api/documents/{id}/status
    A->>M: Verify Token (admin)
    M->>C: RegistrationReviewController@updateStatus
    C->>Mod: SubmissionDocument::update()
    Mod->>DB: UPDATE submission_documents
    DB-->>Mod: Success
    Mod-->>C: Updated Document
    C-->>A: JSON Success
    A-->>F: Status Updated
```

## 5. Flutter App Architecture

```mermaid
graph TB
    subgraph "Presentation Layer"
        A[Screens] --> B[Widgets]
    end
    
    subgraph "Business Logic Layer"
        C[Providers] --> D[Auth Provider]
        C --> E[Competition Provider]
    end
    
    subgraph "Data Layer"
        F[Services] --> G[API Service]
        F --> H[Storage Service]
    end
    
    subgraph "Model Layer"
        I[Models] --> J[User Model]
        I --> K[Competition Model]
        I --> L[Registration Model]
        I --> M[Document Model]
    end
    
    A --> C
    C --> F
    F --> N[Laravel API]
    F --> I
```

## 6. State Management Flow (Flutter)

```mermaid
flowchart LR
    A[UI Screen] -->|User Action| B[Provider]
    B -->|Call Service| C[API Service]
    C -->|HTTP Request| D[Laravel Backend]
    D -->|HTTP Response| C
    C -->|Parse JSON| E[Model]
    E -->|Update State| B
    B -->|notifyListeners| A
    A -->|Rebuild UI| F[Updated Screen]
```

## 7. Middleware & Authorization Flow

```mermaid
flowchart TD
    A[HTTP Request] --> B{Authenticated?}
    B -->|Tidak| C[Redirect to Login]
    B -->|Ya| D{Check Role}
    
    D -->|admin| E[Admin Routes]
    E --> E1[/admin/competitions]
    E --> E2[/admin/registrations]
    E --> E3[/admin/documents]
    
    D -->|peserta| F[Peserta Routes]
    F --> F1[/peserta/competitions]
    F --> F2[/peserta/registrations]
    F --> F3[/peserta/upload]
    
    D -->|Invalid| G[403 Forbidden]
    
    E1 --> H[Controller Action]
    E2 --> H
    E3 --> H
    F1 --> H
    F2 --> H
    F3 --> H
    
    H --> I[Response]
```

## 8. File Upload & Storage Flow

```mermaid
flowchart TD
    A[User Upload File] --> B{File Type Valid?}
    B -->|Tidak| C[Error: Invalid Type]
    B -->|Ya| D{File Size OK?}
    D -->|Tidak| E[Error: File Too Large]
    D -->|Ya| F[Laravel Storage Handler]
    
    F --> G{Storage Type}
    G -->|Poster| H[storage/app/public/posters]
    G -->|Guidebook| I[storage/app/public/guidebooks]
    G -->|Submission| J[storage/app/public/submissions]
    
    H --> K[Generate Unique Name]
    I --> K
    J --> K
    
    K --> L[Save to Disk]
    L --> M[Save Path to Database]
    M --> N[Return Success + URL]
```

## 9. Competition Stages Flow

```mermaid
flowchart TD
    A[Peserta Mendaftar] --> B[Registration Created]
    B --> C[Stage 1: Pendaftaran]
    
    C --> D[Upload Dokumen Stage 1]
    D --> E{Admin Review}
    E -->|Rejected| F[Perbaiki Dokumen]
    F --> D
    E -->|Approved| G[Stage 2: Seleksi Berkas]
    
    G --> H[Upload Dokumen Stage 2]
    H --> I{Admin Review}
    I -->|Rejected| J[Perbaiki Dokumen]
    J --> H
    I -->|Approved| K[Stage 3: Final]
    
    K --> L[Upload Dokumen Stage 3]
    L --> M{Admin Review}
    M -->|Rejected| N[Perbaiki Dokumen]
    N --> L
    M -->|Approved| O[Kompetisi Selesai]
```

## 10. Data Validation Flow

```mermaid
flowchart TD
    A[User Input] --> B[Frontend Validation]
    B -->|Valid| C[Send to Backend]
    B -->|Invalid| D[Show Error Message]
    
    C --> E[Laravel Request Validation]
    E -->|Valid| F[Process Data]
    E -->|Invalid| G[Return 422 Error]
    G --> H[Show Validation Errors]
    
    F --> I[Business Logic]
    I --> J[Save to Database]
    J --> K[Return Success]
```

## Penjelasan Komponen Utama

### Backend (Laravel)
- **Models**: User, Competition, Registration, DocumentTemplate, SubmissionDocument
- **Controllers**: 
  - Admin: Manajemen kompetisi, review pendaftaran
  - Peserta: Browse kompetisi, daftar, upload dokumen
- **Middleware**: Auth (Laravel Sanctum/Session), Role-based access
- **Storage**: File system untuk poster, guidebook, dan submission documents

### Frontend (Flutter)
- **Providers**: State management menggunakan Provider pattern
- **Screens**: UI untuk berbagai fitur (login, home, competitions, etc.)
- **Services**: HTTP client untuk komunikasi dengan Laravel API
- **Models**: Dart classes untuk data models

### Database
- **users**: Data pengguna dengan role (admin/peserta)
- **competitions**: Data kompetisi
- **registrations**: Pendaftaran peserta ke kompetisi
- **document_templates**: Template dokumen yang harus diupload
- **submission_documents**: Dokumen yang diupload peserta

### Alur Utama
1. **Admin** membuat kompetisi dan template dokumen
2. **Peserta** melihat dan mendaftar kompetisi
3. **Peserta** upload dokumen sesuai template
4. **Admin** review dan approve/reject dokumen
5. Proses berlanjut sesuai stages kompetisi
