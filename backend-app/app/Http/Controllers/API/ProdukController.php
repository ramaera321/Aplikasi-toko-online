<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function getAllData(){
        $produk = Produk::count();
        $kategori = Kategori::count();
        $transaksi = Transaksi::count();
        $bukukas = BukuKas::orderBy('created_at', 'DESC')->first();

        $data = [
            'produk' => $produk,
            'kategori' => $kategori,
            'transaksi' => $transaksi,
            'bukukas' => $bukukas,
        ];

        return response()->json(['message' => 'Sukses mengambil semua data', 'data' => $data], 200);
    }

    public function getProduks(){
        $produks = Produk::with('kategori')->get();

        return response()->json(['message' => 'Sukses mengambil produk', 'data' => $produks], 200);
    }

    public function getProduksPaginate(){
        $produks = Produk::with('kategori')->paginate();

        return response()->json(['message' => 'Sukses mengambil produk', 'data' => $produks], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'kategori_id' => 'required',
            'nama_produk' => 'required|string',
            'deskripsi' => 'required',
            'stok' => 'required|integer',
            'harga' => 'required|integer',
            'path' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $image_path = '/image/default-image.png';
        if ($request->file() || $request->file('path') != null)
            $image_path = $request->file('path')->store('image', 'public');

        DB::beginTransaction();
        try {
            $produk = Produk::create([
                'kategori_id' => $request->kategori_id,
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'path' => $image_path,
            ]);

            DB::commit();
            return response()->json(['message' => 'Sukses menginputkan produk', 'data' => $produk], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menginputkan produk' . $e->getMessage()], 400);
        }
    }

    public function show($id){
        $produk = Produk::find($id);

        return response()->json(['message' => 'Sukses mengambil produk', 'data' => $produk], 200);
    }

    public function update(Request $request, $id){
        
        if($request->input() == NULL){
            return response()->json(['message' => 'Tidak ada data yang diubah'], 400);
        }

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($id);

            $image_path = $produk->path;
            if ($request->file() || $request->file('path') != null)
                $image_path = $request->file('path')->store('image/book', 'public');

            $produk->update([
                'kategori_id' => $request->kategori_id,
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'path' => $image_path,
            ]);

            DB::commit();            
            return response()->json(['message' => 'Sukses mengubah produk', 'data' => $produk], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal mengubah produk'], 400);
        }

    }

    public function destroy($id){
        $produk = Produk::find($id);
        if($produk->delete()) {
            return response()->json(['message' => 'Sukses menghapus produk'], 200);
        } else {
            return response()->json(['message' => 'Gagal menghapus produk'], 400);
        }
    }
}
