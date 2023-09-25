<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 20; $i++){
            Produk::create([
                'kategori_id' => rand(1, 4),
                'nama_produk' => 'Produk ' . $i,
                'deskripsi' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, at odio. Quae at quos fugit vel adipisci facilis dolorum hic, beatae repellat exercitationem doloremque eligendi soluta harum minus, dolore molestiae.',
                'stok' => 200,
                'harga' => 5000,
                'path' => '/image/default-image.png',
            ]);
        }
    }
}
