<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Competition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // User Peserta
        User::firstOrCreate(
            ['email' => 'peserta1@example.com'],
            [
                'name' => 'Peserta 1',
                'password' => Hash::make('password'),
                'role' => 'peserta',
            ]
        );

        User::firstOrCreate(
            ['email' => 'peserta2@example.com'],
            [
                'name' => 'Peserta 2',
                'password' => Hash::make('password'),
                'role' => 'peserta',
            ]
        );

        // Sample Competitions
        Competition::firstOrCreate(
            ['title' => 'Lomba Karya Tulis Ilmiah 2025'],
            [
                'description' => "Lomba Karya Tulis Ilmiah Nasional untuk mahasiswa se-Indonesia.\n\nTema: Inovasi Teknologi untuk Masa Depan",
                'stages' => "1. Pendaftaran & Upload Proposal\n2. Seleksi Administrasi\n3. Presentasi Final\n4. Pengumuman Pemenang",
                'source_link' => 'https://example.com/lkti',
            ]
        );

        Competition::firstOrCreate(
            ['title' => 'Lomba Desain Grafis 2025'],
            [
                'description' => "Kompetisi desain grafis untuk pelajar dan mahasiswa.\n\nKategori: Poster Digital, Infografis",
                'stages' => "1. Pendaftaran Online\n2. Upload Karya\n3. Penjurian\n4. Pengumuman Pemenang",
                'source_link' => 'https://example.com/design',
            ]
        );

        Competition::firstOrCreate(
            ['title' => 'Hackathon 2025'],
            [
                'description' => "Kompetisi pemrograman 48 jam untuk membuat aplikasi inovatif.\n\nPrize Pool: 50 Juta Rupiah",
                'stages' => "1. Registrasi Tim (max 4 orang)\n2. Babak Penyisihan\n3. Hackathon 48 jam\n4. Demo Day & Penjurian",
            ]
        );
    }
}

