<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\User;
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
        // 1. Akun Admin Perpustakaan
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Data Awal Buku
        $bukuData = [
            [
                'judul' => 'Pemrograman Laravel',
                'penulis' => 'Taylor Otwell',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2023,
                'stok' => 10,
            ],
            [
                'judul' => 'Basis Data',
                'penulis' => 'Fathansyah',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2020,
                'stok' => 8,
            ],
            [
                'judul' => 'Algoritma dan Pemrograman',
                'penulis' => 'Rinaldi Munir',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2021,
                'stok' => 5,
            ],
            [
                'judul' => 'Rekayasa Perangkat Lunak',
                'penulis' => 'Roger S. Pressman',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2019,
                'stok' => 7,
            ],
            [
                'judul' => 'Sistem Informasi',
                'penulis' => 'Kenneth C. Laudon',
                'penerbit' => 'Salemba Empat',
                'tahun_terbit' => 2022,
                'stok' => 4,
            ],
        ];

        foreach ($bukuData as $buku) {
            Buku::create($buku);
        }

        // 3. Data Awal Anggota
        $anggotaData = [
            [
                'nama' => 'Ahmad Pratama',
                'email' => 'ahmad@example.com',
                'no_hp' => '081234567801',
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'no_hp' => '081234567802',
            ],
            [
                'nama' => 'Citra Dewi',
                'email' => 'citra@example.com',
                'no_hp' => '081234567803',
            ],
            [
                'nama' => 'Dina Lestari',
                'email' => 'dina@example.com',
                'no_hp' => '081234567804',
            ],
            [
                'nama' => 'Eko Wahyudi',
                'email' => 'eko@example.com',
                'no_hp' => '081234567805',
            ],
        ];

        foreach ($anggotaData as $anggota) {
            Anggota::create($anggota);
        }
    }
}
