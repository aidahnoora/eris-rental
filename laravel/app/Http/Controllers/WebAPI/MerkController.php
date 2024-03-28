<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\MerkMobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerkController extends Controller
{
    public function index()
    {
        $merks = MerkMobil::paginate(100);

        return new GeneralResource(true, 'List Data Merk Mobils', $merks);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $merk = MerkMobil::create([
            'nama' => $request->nama,
        ]);

        return new GeneralResource(true, 'Data Merk Berhasil Ditambahkan!', $merk);
    }

    public function show($id)
    {
        $merk = MerkMobil::find($id);

        return new GeneralResource(true, 'Detail Data Merk!', $merk);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $merk = MerkMobil::find($id);

        $merk->update([
            'nama' => $request->nama,
        ]);

        return new GeneralResource(true, 'Data Merk Berhasil Diubah!', $merk);
    }

    public function destroy($id)
    {
        $merk = MerkMobil::find($id);

        $merk->delete();

        return new GeneralResource(true, 'Data Merk Berhasil Dihapus!', null);
    }
}
