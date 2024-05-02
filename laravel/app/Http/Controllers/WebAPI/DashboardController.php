<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\Customer;
use App\Models\DataMobil;
use App\Models\MerkMobil;
use App\Models\TransaksiPenyewaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getTransaction()
    {
        $jumlahTransaksiPerBulan = TransaksiPenyewaan::select(
            DB::raw('
                CASE EXTRACT(MONTH FROM tgl_sewa)
                WHEN 1 THEN \'Januari\'
                WHEN 2 THEN \'Februari\'
                WHEN 3 THEN \'Maret\'
                WHEN 4 THEN \'April\'
                WHEN 5 THEN \'Mei\'
                WHEN 6 THEN \'Juni\'
                WHEN 7 THEN \'Juli\'
                WHEN 8 THEN \'Agustus\'
                WHEN 9 THEN \'September\'
                WHEN 10 THEN \'Oktober\'
                WHEN 11 THEN \'November\'
                WHEN 12 THEN \'Desember\'
            END AS bulan
            '),
            DB::raw('COUNT(*) as jumlah_transaksi')
        )
            ->whereYear('tgl_sewa', date('Y'))
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tgl_sewa)'))
            ->get();

        // dd($jumlahTransaksiPerBulan);

        return new GeneralResource(true, 'List Jumlah Transaksi Penyewaan', $jumlahTransaksiPerBulan);
    }

    public function getCategories()
    {
        $categories = MerkMobil::count();
        $books = DataMobil::count();
        $members = Customer::count();
        $raks = User::count();

        return new GeneralResource(true, 'Get All Count for Card', [
            'categories' => $categories,
            'books' => $books,
            'members' => $members,
            'raks' => $raks,

        ]);
    }
}
