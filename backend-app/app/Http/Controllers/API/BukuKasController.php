<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;
use Illuminate\Http\Request;

class BukuKasController extends Controller
{
    public function getBukuKas(){
        $buku_kas = BukuKas::with(['transaksi' => function ($q){
            $q->with('pembeli', 'produk');
        }])->get();

        return response()->json(['message' => 'Berhasil mengambil data buku kas', 'data' => $buku_kas], 200);
    }
}
