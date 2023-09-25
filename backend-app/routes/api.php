<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    // Logout
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

    //All Data
    Route::get('/all', [App\Http\Controllers\API\ProdukController::class, 'getAllData']);

    // Produk
    Route::get('/produk', [App\Http\Controllers\API\ProdukController::class, 'getProduks']);
    Route::get('/produk/paginate', [App\Http\Controllers\API\ProdukController::class, 'getProduksPaginate']);
    Route::post('/produk/store', [App\Http\Controllers\API\ProdukController::class, 'store']);
    Route::get('/produk/{id}/show', [App\Http\Controllers\API\ProdukController::class, 'show']);
    Route::post('/produk/{id}/update', [App\Http\Controllers\API\ProdukController::class, 'update']);
    Route::delete('/produk/{id}/delete', [App\Http\Controllers\API\ProdukController::class, 'destroy']);

    // Kategori
    Route::get('/kategori', [App\Http\Controllers\API\KategoriController::class, 'getKategoris']);
    Route::post('/kategori/store', [App\Http\Controllers\API\KategoriController::class, 'store']);
    Route::get('/kategori/{id}/show', [App\Http\Controllers\API\KategoriController::class, 'show']);
    Route::post('/kategori/{id}/update', [App\Http\Controllers\API\KategoriController::class, 'update']);
    Route::delete('/kategori/{id}/delete', [App\Http\Controllers\API\KategoriController::class, 'destroy']);

    // Transaksi
    Route::get('/transaksi', [App\Http\Controllers\API\TransaksiController::class, 'getTransaksis']);
    Route::get('/transaksi/user/{id}/count', [App\Http\Controllers\API\TransaksiController::class, 'getCountTransaksiUser']);
    Route::get('/transaksi/user/{id}', [App\Http\Controllers\API\TransaksiController::class, 'getTransaksiUser']);
    Route::post('/transaksi/store', [App\Http\Controllers\API\TransaksiController::class, 'store']);
    Route::post('/transaksi/bayar/{id}', [App\Http\Controllers\API\TransaksiController::class, 'bayar']);

    // Buku Kas
    Route::get('/bukukas', [App\Http\Controllers\API\BukuKasController::class, 'getBukuKas']);
});
