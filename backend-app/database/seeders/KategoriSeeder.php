<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = ['Makanan', 'Minuman', 'Alat Tulis', 'Perlengkapan Kebersihan'];

        foreach($kategori as $k){
            Kategori::create([
                'nama_kategori' => $k,
            ]);
        }
    }
}
