# 🏆 Sistem Pendaftaran Perlombaan

Web aplikasi untuk mengelola pendaftaran perlombaan berbasis Laravel 11 dengan fitur untuk Admin dan Peserta.

## 🎯 Fitur Utama

### Admin
- ✅ CRUD Lomba (Create, Read, Update, Delete)
- ✅ Upload poster dan guidebook lomba
- ✅ Kelola template dokumen (Proposal, Surat Rekomendasi)
- ✅ Lihat daftar peserta yang mendaftar
- ✅ Review dan koreksi dokumen peserta
- ✅ Berikan catatan/feedback ke peserta

### Peserta
- ✅ Lihat daftar lomba yang tersedia
- ✅ Lihat detail lomba dan download template
- ✅ Daftar lomba
- ✅ Upload dokumen (Proposal, Surat Rekomendasi)
- ✅ Lihat status review dokumen dari admin

## 🛠️ Teknologi

- **Framework:** Laravel 11
- **Database:** MySQL
- **Frontend:** Blade Templates, Tailwind CSS
- **Authentication:** Laravel Breeze

## 📦 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd tugas-akhir
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan dengan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

### 5. Migrasi & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 6. Setup Storage Link
```bash
php artisan storage:link
```

### 7. Build Assets
```bash
npm run dev
# atau untuk production
npm run build
```

### 8. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👤 Akun Testing

Setelah menjalankan seeder, Anda dapat login dengan:

**Admin:**
- Email: `admin@example.com`
- Password: `password`

**Peserta:**
- Email: `peserta1@example.com`
- Password: `password`

## 📂 Struktur Fitur

### Routes
- `/admin/*` - Area admin (dashboard, CRUD lomba, review dokumen)
- `/peserta/*` - Area peserta (lihat lomba, daftar, upload dokumen)

### Models
- `User` - Model untuk user (admin & peserta)
- `Competition` - Model untuk lomba
- `Registration` - Model untuk pendaftaran peserta ke lomba
- `DocumentTemplate` - Model untuk template dokumen yang disediakan admin
- `SubmissionDocument` - Model untuk dokumen yang diupload peserta

## 🔐 Role & Permission

Sistem menggunakan middleware `role` untuk membedakan akses:
- **Admin:** Akses penuh untuk mengelola lomba dan review dokumen
- **Peserta:** Akses untuk melihat lomba dan mendaftar

## 📝 Cara Menggunakan

### Sebagai Admin:
1. Login dengan akun admin
2. Buat lomba baru dengan poster dan guidebook
3. Upload template dokumen untuk lomba tersebut
4. Tunggu peserta mendaftar
5. Review dokumen yang diupload peserta
6. Berikan feedback/koreksi

### Sebagai Peserta:
1. Login dengan akun peserta
2. Lihat daftar lomba yang tersedia
3. Download template dokumen
4. Daftar ke lomba yang diminati
5. Upload dokumen yang diperlukan
6. Lihat status review dari admin

## 🐛 Troubleshooting

### Permission Error
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:$USER storage bootstrap/cache
```

### Storage Link Error
```bash
php artisan storage:link
```

### Migration Error
```bash
php artisan migrate:fresh --seed
```

## 📌 TODO / Pengembangan Selanjutnya

- [ ] Email notification untuk peserta
- [ ] Export data peserta ke Excel/PDF
- [ ] Dashboard statistik untuk admin
- [ ] Filter dan pencarian lomba
- [ ] Multi-stage registration tracking
- [ ] Payment gateway integration

## 📄 Lisensi

Projek ini dibuat untuk tugas akhir Pemrograman Lanjutan.

---

Dibuat dengan ❤️ menggunakan Laravel