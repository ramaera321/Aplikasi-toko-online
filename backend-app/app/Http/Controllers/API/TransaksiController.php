<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function getTransaksis(){
        $transaksis = Transaksi::with(['produk', 'kas', 'pembeli'])->get();

        return response()->json(['message' => 'Berhasil mengambil semua data transaksi', 'data' => $transaksis], 200);
    }

    public function getTransaksiUser($id){
        $transaksis = Transaksi::where('pembeli_id', $id)->with(['produk'])->get();

        return response()->json(['message' => 'Berhasil mengambil semua data transaksi user', 'data' => $transaksis], 200);
    }

    public function getCountTransaksiUser($id){
        $transaksis = Transaksi::where('pembeli_id', $id)->with(['produk'])->get();
        $sudah_dibayar = $transaksis->where('dibayar', '!=', NULL)->count();
        $belum_dibayar = $transaksis->where('dibayar', NULL)->count();

        $data = [
            'transaksi' => $transaksis->count(),
            'sudah_dibayar' => $sudah_dibayar,
            'belum_dibayar' => $belum_dibayar,
        ];

        return response()->json(['message' => 'Berhasil mengambil semua data transaksi user', 'data' => $data], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'pembeli_id' => 'required',
            'produk_id' => 'required',
            'jumlah_produk' => 'required',
            'total_transaksi' => 'required',
            'tanggal_transaksi' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create($request->all());
            $produk = Produk::findOrFail($transaksi->produk_id);
            
            $produk->update([
                'stok' => $produk->stok - $transaksi->jumlah_produk,
            ]);

            DB::commit();
            return response()->json(['message' => 'Berhasil melakukan transaksi', 'data' => $transaksi], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal melakukan transaksi' . $e->getMessage()], 400);
        }
    }

    public function bayar(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'dibayar' => 'required',
            'kembalian' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            $transaksi->update([
                'dibayar' => $request->dibayar,
                'kembalian' => $request->kembalian,
            ]);

            $last_buku_kas = BukuKas::orderBy('id', 'DESC')->first();

            $saldo_awal = $last_buku_kas != null ? $last_buku_kas->saldo_awal : 0;
            $saldo_akhir = $saldo_awal + $transaksi->total_transaksi;

            $buku_kas = BukuKas::create([
                'transaksi_id' => $id,
                'saldo_awal' => $saldo_awal,
                'saldo_akhir' => $saldo_akhir,
            ]);

            DB::commit();
            return response()->json(['message' => 'Berhasil melakukan transaksi', 'data' => $transaksi, 'kas' => $buku_kas], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal melakukan transaksi' . $e->getMessage()], 400);
        }
    }
}
