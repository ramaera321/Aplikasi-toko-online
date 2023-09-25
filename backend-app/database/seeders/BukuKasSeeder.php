<?php

namespace Database\Seeders;

use App\Models\BukuKas;
use Illuminate\Database\Seeder;

class BukuKasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BukuKas::create([
            'transaksi_id' => 0,
            'saldo_awal' => 0,
            'saldo_akhir' => 0,
        ]);
    }
}
