<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\DataMobil;
use App\Models\MerkMobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MobilController extends Controller
{
    public function index()
    {
        $mobils = DataMobil::with('merk')->paginate(100);

        return new GeneralResource(true, 'List Data Mobil', $mobils);
    }

    public function getMerks()
    {
        $merks = MerkMobil::paginate(100);

        return new GeneralResource(true, 'List Merks', $merks);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merk_id' => 'required',
            'nama' => 'required',
            'stok' => 'required',
            'model' => 'required',
            'tahun_buat' => 'required',
            'nomor_plat' => 'required',
            'kapasitas' => 'required',
            'bbm' => 'required',
            'harga' => 'required',
            'transmisi' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $mobils = DataMobil::create([
            'merk_id' => $request->merk_id,
            'nama' => $request->nama,
            'stok' => $request->stok,
            'model' => $request->model,
            'tahun_buat' => $request->tahun_buat,
            'nomor_plat' => $request->nomor_plat,
            'kapasitas' => $request->kapasitas,
            'bbm' => $request->bbm,
            'harga' => $request->harga,
            'transmisi' => $request->transmisi,
            'image' => $image->hashName(),
        ]);

        return new GeneralResource(true, 'Data Mobils Berhasil Ditambahkan!', $mobils);
    }

    public function show($id)
    {
        $mobils = DataMobil::with('merk')->find($id);

        return new GeneralResource(true, 'Detail Data Mobils!', $mobils);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'merk_id' => 'required',
            'nama' => 'required',
            'stok' => 'required',
            'model' => 'required',
            'tahun_buat' => 'required',
            'nomor_plat' => 'required',
            'kapasitas' => 'required',
            'bbm' => 'required',
            'harga' => 'required',
            'transmisi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mobil = DataMobil::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts/' . basename($mobil->image));

            $mobil->update([
                'merk_id' => $request->merk_id,
                'nama' => $request->nama,
                'stok' => $request->stok,
                'model' => $request->model,
                'tahun_buat' => $request->tahun_buat,
                'nomor_plat' => $request->nomor_plat,
                'kapasitas' => $request->kapasitas,
                'bbm' => $request->bbm,
                'harga' => $request->harga,
                'transmisi' => $request->transmisi,
                'image' => $image->hashName(),
            ]);
        } else {
            $mobil->update([
                'merk_id' => $request->merk_id,
                'nama' => $request->nama,
                'stok' => $request->stok,
                'model' => $request->model,
                'tahun_buat' => $request->tahun_buat,
                'nomor_plat' => $request->nomor_plat,
                'kapasitas' => $request->kapasitas,
                'bbm' => $request->bbm,
                'harga' => $request->harga,
                'transmisi' => $request->transmisi,
            ]);
        }

        return new GeneralResource(true, 'Data Mobil Berhasil Diubah!', $mobil);
    }

    public function destroy($id)
    {
        $mobil = DataMobil::find($id);

        $mobil->delete();

        return new GeneralResource(true, 'Data Mobil Berhasil Dihapus!', null);
    }

    public function filterByStatus($status)
    {
        if ($status == 'ada') {
            $mobils = DataMobil::where('stok', '>', 0)->get();
        } else if ($status == 'kosong') {
            $mobils = DataMobil::where('stok', 0)->get();
        } else {
            $mobils = DataMobil::all();
        }

        return response()->json(['data' => $mobils]);
    }
}
