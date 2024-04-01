<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPenyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'mobil_id',
        'subtotal',
    ];

    public function mobil()
    {
        return $this->belongsTo(DataMobil::class, 'mobil_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenyewaan::class, 'transaksi_id');
    }
}
