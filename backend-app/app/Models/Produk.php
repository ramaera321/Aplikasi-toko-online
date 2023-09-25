<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id', 'nama_produk', 'deskripsi', 'stok', 'harga', 'path'
    ];

    public function kategori(){
        return $this->hasOne(Kategori::class, 'id', 'kategori_id');
    }

    public function transaksis(){
        return $this->hasMany(Transaksi::class, 'produk_id', 'id');
    }
}
