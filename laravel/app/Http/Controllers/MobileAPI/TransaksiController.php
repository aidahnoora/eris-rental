<?php

namespace App\Http\Controllers\MobileAPI;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\DataMobil;
use App\Models\DetailTransaksiPenyewaan;
use App\Models\TransaksiPenyewaan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function all(Request $request)
    {
        $limit = $request->input('limit');
        $id = $request->input('id');
        $status = $request->input('status');

        if ($id) {
            $transaksi = TransaksiPenyewaan::with(['customer'])->find($id);

            if ($transaksi) {
                return ResponseFormatter::success(
                    $transaksi,
                    'Data transaksi berhasil diambil',
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
            }
        }

        $transaksi = TransaksiPenyewaan::with(['customer'])
            ->where('user_id', Auth::user()->id);

        if ($status) {
            $transaksi->where('status', $status);
        }

        return ResponseFormatter::success([
            $transaksi->orderBy('created_at', 'desc')->paginate(100),
            'Data transaksi berhasil diambil',
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required',
            'total_bayar' => 'required',
        ]);

        try {
            $transaksi = TransaksiPenyewaan::create([
                'customer_id' => Auth::user()->customer->id,
                'user_id' => Auth::user()->id,
                'tgl_sewa' => $request->tgl_sewa,
                'tgl_kembali' => $request->tgl_kembali,
                'durasi_sewa' => $request->durasi_sewa,
                'total_bayar' => $request->total_bayar,
            ]);

            foreach ($request->items as $mobil) {
                DetailTransaksiPenyewaan::create([
                    'transaksi_id' => $transaksi->id,
                    'mobil_id' => $mobil['id'],
                    'subtotal' => $mobil['subtotal'],
                ]);

                $mobil = DataMobil::where('id', $mobil['id'])->first();

                if ($mobil) {
                    $mobil->stok -= 1;
                    $mobil->save();
                };
            }

            $detail = DetailTransaksiPenyewaan::where('transaksi_id', $transaksi->id)->get();

            return ResponseFormatter::success([
                $transaksi,
                $detail,
                'Transaksi berhasil',
            ]);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error->getMessage(),
            ], 'Transaction Failed', 500);
        }
    }
}
