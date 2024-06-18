<?php

namespace App\Http\Controllers\MobileAPI;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\MerkMobil;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    public function all(Request $request)
    {
        $limit = $request->input('limit');
        $id = $request->input('id');
        $nama = $request->input('nama');
        $show_mobil = $request->input('show_mobil');

        if ($id) {
            $merk = MerkMobil::find($id);

            if ($merk) {
                return ResponseFormatter::success(
                    $merk,
                    'Data Merk berhasil diambil',
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Merk tidak ada',
                    404
                );
            }
        }

        $merk = MerkMobil::query();

        if ($show_mobil) {
            $merk->with('produks');
        }

        return ResponseFormatter::success(
            $merk->paginate(100),
            'Data merk berhasil diambil',
        );
    }
}
