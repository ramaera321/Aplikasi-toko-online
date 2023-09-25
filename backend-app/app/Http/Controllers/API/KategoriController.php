<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function getKategoris(){
        $kategoris = Kategori::get();

        return response()->json(['message' => 'Sukses mengambil Kategori', 'data' => $kategoris], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nama_kategori' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        DB::beginTransaction();
        try {
            $kategori = Kategori::create($request->all());

            DB::commit();
            return response()->json(['message' => 'Sukses menginputkan Kategori', 'data' => $kategori], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menginputkan Kategori' . $e->getMessage()], 400);
        }
    }

    public function show($id){
        $kategori = Kategori::find($id);

        return response()->json(['message' => 'Sukses mengambil Kategori', 'data' => $kategori], 200);
    }

    public function update(Request $request, $id){
        
        if($request->input() == NULL){
            return response()->json(['message' => 'Tidak ada data yang diubah'], 400);
        }

        DB::beginTransaction();
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->update($request->all());

            DB::commit();            
            return response()->json(['message' => 'Sukses mengubah Kategori', 'data' => $kategori], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal mengubah Kategori'], 400);
        }

    }

    public function destroy($id){
        $kategori = Kategori::find($id);
        if($kategori->delete()) {
            return response()->json(['message' => 'Sukses menghapus Kategori'], 200);
        } else {
            return response()->json(['message' => 'Gagal menghapus Kategori'], 400);
        }
    }
}
