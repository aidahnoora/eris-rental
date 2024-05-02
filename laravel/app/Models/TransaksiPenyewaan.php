<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        // 'nota',
        'tgl_sewa',
        'tgl_kembali',
        'durasi_sewa',
        'tgl_pengembalian_mobil',
        'status',
        'lama_telat',
        'denda_per_hari',
        'total_denda',
        'total_bayar',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail_transaksis(){
        return $this->hasMany(DetailTransaksiPenyewaan::class);
    }
}
