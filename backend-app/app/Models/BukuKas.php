<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKas extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'saldo_awal',
        'saldo_akhir',
    ];

    protected $table = 'buku_kas';

    public function transaksi(){
        return $this->hasOne(Transaksi::class, 'id', 'transaksi_id');
    }
}
