<?php

namespace App\Http\Controllers\MobileAPI;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\DataMobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $nama = $request->input('nama');

        if ($id) {
            $mobil = DataMobil::with('merk')->find($id);

            if ($mobil) {
                return ResponseFormatter::success(
                    $mobil,
                    'Data mobil berhasil diambil',
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data mobil tidak ada',
                    404
                );
            }
        }

        $mobil = DataMobil::with('merk');

        if ($nama) {
            $mobil->where('nama', 'like', '%' . $nama . '%');
        }

        return ResponseFormatter::success(
            // $mobil->paginate(100),
            $mobil->where('stok', '>', 0)->paginate(100),
            'Data mobil berhasil diambil',
        );
    }
}
