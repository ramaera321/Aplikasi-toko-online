<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembeli_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('jumlah_produk');
            $table->integer('total_transaksi');
            $table->integer('dibayar')->nullable();
            $table->integer('kembalian')->nullable();
            $table->date('tanggal_transaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
