<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\Customer;
use App\Models\DataMobil;
use App\Models\DetailTransaksiPenyewaan;
use App\Models\TransaksiPenyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPenyewaan::with('customer')
            ->with('user')
            ->paginate(100);

        return new GeneralResource(true, 'List Data Transaksi', $transaksis);
    }

    public function getCustomers()
    {
        $customers = Customer::paginate(100);

        return new GeneralResource(true, 'List Customer', $customers);
    }

    public function getMobils()
    {
        $mobils = DataMobil::paginate(100);

        return new GeneralResource(true, 'List Mobil', $mobils);
    }

    public function getItemTransaksis($transaksiId)
    {
        $itemTransaksis = DetailTransaksiPenyewaan::with('mobil')->where('transaksi_id', $transaksiId)
            ->paginate(100);

        return new GeneralResource(true, 'List Item Transaksi', $itemTransaksis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'nota' => 'required',
            'tgl_sewa' => 'required',
            'tgl_kembali' => 'required',
            'durasi_sewa' => 'required',
            'status' => 'required',
            'total_bayar' => 'required',
            'items' => 'required|array',
            'items.*.mobil_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaksi = DB::transaction(function () use ($request) {
            $header = TransaksiPenyewaan::create([
                'user_id' => 1,
                'customer_id' => $request->customer_id,
                'nota' => $request->nota,
                'tgl_sewa' => $request->tgl_sewa,
                'tgl_kembali' => $request->tgl_kembali,
                'durasi_sewa' => $request->durasi_sewa,
                'status' => $request->status,
                'total_bayar' => $request->total_bayar,
            ]);

            $items = [];
            foreach ($request->items as $item) {
                $items[] = [
                    'transaksi_id' => $header->id,
                    'mobil_id' => $item['mobil_id'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'created_at' => now()
                ];
            }

            DetailTransaksiPenyewaan::insert($items);

            // Update otomatis stok data barang
            foreach ($request->items as $item) {
                DataMobil::where('id', $item['mobil_id']);
            }

            return $header;
        });

        return new GeneralResource(true, 'Data Transaksi Berhasil Ditambahkan!', $transaksi);
    }

    public function show($id)
    {
        $transaksi = TransaksiPenyewaan::with('customer')->find($id);

        return new GeneralResource(true, 'Detail Data Transaksi!', $transaksi);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'nota' => 'required',
            'status' => 'required',
            'tgl_pengembalian_mobil' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaksi = TransaksiPenyewaan::find($id);

        $transaksi->update([
            'customer_id' => $request->customer_id,
            'nota' => $request->nota,
            'tgl_pengembalian_mobil' => $request->tgl_pengembalian_mobil,
            'status' => $request->status,
            'lama_telat' => $request->lama_telat,
            'denda_per_hari' => $request->denda_per_hari,
            'total_denda' => $request->total_denda,
            'total_bayar' => $request->total_bayar,
        ]);

        return new GeneralResource(true, 'Data Transaksi Berhasil Diubah!', $transaksi);
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPenyewaan::find($id);

        $transaksi->delete();

        return new GeneralResource(true, 'Data Transaksi Berhasil Dihapus!', null);
    }

    public function report(Request $request)
    {
        $bulanTahun = $request->query('bulan_tahun');
        $tahun = $request->query('tahun');

        $transaksiPenjualan = TransaksiPenyewaan::with('customer')
            ->whereMonth('tgl_transaksi', date('m', strtotime($bulanTahun)))
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        return response()->json(['data' => $transaksiPenjualan]);
    }
}
