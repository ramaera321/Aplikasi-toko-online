<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembeli_id',
        'produk_id',
        'jumlah_produk',
        'total_transaksi',
        'tanggal_transaksi',
        'dibayar',
        'kembalian',
    ];

    public function produk(){
        return $this->hasOne(Produk::class, 'id', 'produk_id');
    }

    public function pembeli(){
        return $this->hasOne(User::class, 'id', 'pembeli_id');
    }

    public function kas(){
        return $this->hasOne(BukuKas::class, 'id', 'transaksi_id');
    }
}
