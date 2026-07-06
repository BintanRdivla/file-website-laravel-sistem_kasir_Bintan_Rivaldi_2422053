<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar nama kategori retail tanpa menyertakan kolom description
        $categories = [
            'Makanan Ringan',
            'Minuman',
            'Bahan Pokok',
            'Kebutuhan Rumah Tangga',
            'Perawatan Tubuh',
            'Kosmetik & Kecantikan',
            'Kesehatan & Obat',
            'Perlengkapan Bayi',
            'Alat Tulis Kantor (ATK)',
            'Elektronik & Gadget',
            'Pakaian & Aksesoris',
            'Peralatan Dapur',
            'Sembako'
        ];

        foreach ($categories as $catName) {
            // Menggunakan firstOrCreate agar aman saat dijalankan berkali-kali tanpa duplikat
            Category::firstOrCreate([
                'name' => $catName
            ]);
        }

        $this->command->info('🎉 Sukses! Kategori retail baru berhasil disinkronkan ke database.');

        // 1. BUAT AKUN ADMIN (Menggunakan updateOrCreate agar tidak crash jika email sudah terdaftar)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );

        // 2. BUAT AKUN KASIR
        User::updateOrCreate(
            ['email' => 'kasir@example.com'],
            [
                'name' => 'Kasir',
                'password' => Hash::make('password'),
                'role' => 'Kasir',
            ]
        );

        // 3. BUAT AKUN MANAJER
        User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manajer',
                'password' => Hash::make('password'),
                'role' => 'Manajer',
            ]
        );
    }
}