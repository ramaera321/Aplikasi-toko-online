<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();
        for($i = 1; $i <= 20; $i++){
            Transaksi::create([
                'pembeli_id' => rand(1, 3),
                'produk_id' => rand(1, 20),
                'jumlah_produk' => $i,
                'total_transaksi' => $i * 5000,
                'tanggal_transaksi' => $date->addDays(rand(1, 4))->format('Y-m-d'),
                'dibayar' => 5000,
                'kembalian' => 0,
            ]);
        }
    }
}
