<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun admin
        DB::table('users')->insert([
            'name'       => 'Administrator',
            'email'      => 'admin@digilibrary.id',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Akun petugas
        DB::table('users')->insert([
            'name'       => 'Petugas Perpustakaan',
            'email'      => 'petugas@digilibrary.id',
            'password'   => Hash::make('petugas123'),
            'role'       => 'petugas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Kategori DULU sebelum buku
        $categories = [
            'Fiksi', 'Non-Fiksi', 'Sejarah',
            'Biografi', 'Ilmu Pengetahuan', 'Teknologi',
            'Agama', 'Sastra', 'Hukum', 'Kesehatan'
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'nama_kategori' => $cat,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // 4. Buku SETELAH kategori ada
        $books = [
            ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'kategori' => 'Fiksi', 'isbn' => '978-979-1227-78-2', 'stok' => 5],
            ['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra', 'kategori' => 'Sastra', 'isbn' => '978-979-428-218-3', 'stok' => 3],
            ['judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'penerbit' => 'Gramedia', 'kategori' => 'Non-Fiksi', 'isbn' => '978-602-06-3234-5', 'stok' => 7],
            ['judul' => 'Sapiens', 'penulis' => 'Yuval Noah Harari', 'penerbit' => 'KPG', 'kategori' => 'Sejarah', 'isbn' => '978-602-424-430-1', 'stok' => 4],
            ['judul' => 'The Psychology of Money', 'penulis' => 'Morgan Housel', 'penerbit' => 'Gramedia', 'kategori' => 'Non-Fiksi', 'isbn' => '978-602-06-5234-1', 'stok' => 6],
            ['judul' => 'Negeri 5 Menara', 'penulis' => 'Ahmad Fuadi', 'penerbit' => 'Gramedia', 'kategori' => 'Fiksi', 'isbn' => '978-979-22-5876-5', 'stok' => 4],
            ['judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'penerbit' => 'Prentice Hall', 'kategori' => 'Teknologi', 'isbn' => '978-0-13-235088-4', 'stok' => 3],
            ['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'penerbit' => 'Kompas', 'kategori' => 'Non-Fiksi', 'isbn' => '978-979-709-980-3', 'stok' => 8],
            ['judul' => 'Dilan 1990', 'penulis' => 'Pidi Baiq', 'penerbit' => 'Mizan', 'kategori' => 'Fiksi', 'isbn' => '978-602-418-008-7', 'stok' => 5],
            ['judul' => 'Sejarah Indonesia Modern', 'penulis' => 'M.C. Ricklefs', 'penerbit' => 'Gadjah Mada', 'kategori' => 'Sejarah', 'isbn' => '978-979-420-112-4', 'stok' => 2],
            ['judul' => 'Habibie & Ainun', 'penulis' => 'B.J. Habibie', 'penerbit' => 'THC Mandiri', 'kategori' => 'Biografi', 'isbn' => '978-979-792-430-8', 'stok' => 6],
            ['judul' => 'The Alchemist', 'penulis' => 'Paulo Coelho', 'penerbit' => 'HarperCollins', 'kategori' => 'Fiksi', 'isbn' => '978-0-06-231609-7', 'stok' => 4],
            ['judul' => 'Deep Work', 'penulis' => 'Cal Newport', 'penerbit' => 'Grand Central', 'kategori' => 'Non-Fiksi', 'isbn' => '978-1-4555-8669-1', 'stok' => 5],
            ['judul' => 'Hujan', 'penulis' => 'Tere Liye', 'penerbit' => 'Gramedia', 'kategori' => 'Fiksi', 'isbn' => '978-602-03-2481-4', 'stok' => 7],
            ['judul' => 'Laravel: Up & Running', 'penulis' => 'Matt Stauffer', 'penerbit' => "O'Reilly", 'kategori' => 'Teknologi', 'isbn' => '978-1-4920-4664-3', 'stok' => 3],
            ['judul' => 'Ikigai', 'penulis' => 'Hector Garcia', 'penerbit' => 'Gramedia', 'kategori' => 'Non-Fiksi', 'isbn' => '978-602-06-1234-8', 'stok' => 9],
            ['judul' => 'Pulang', 'penulis' => 'Tere Liye', 'penerbit' => 'Republika', 'kategori' => 'Fiksi', 'isbn' => '978-602-7948-51-4', 'stok' => 4],
            ['judul' => 'Seni Berpikir Jernih', 'penulis' => 'Rolf Dobelli', 'penerbit' => 'Gramedia', 'kategori' => 'Non-Fiksi', 'isbn' => '978-979-22-9876-1', 'stok' => 5],
        ];

        foreach ($books as $book) {
            $kategori = \App\Models\Category::where('nama_kategori', $book['kategori'])->first();
            if ($kategori) {
                DB::table('books')->insert([
                    'judul'       => $book['judul'],
                    'penulis'     => $book['penulis'],
                    'penerbit'    => $book['penerbit'],
                    'kategori_id' => $kategori->id,
                    'isbn'        => $book['isbn'],
                    'stok'        => $book['stok'],
                    'gambar'      => null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}