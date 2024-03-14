<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobil_id',
        'tgl_sewa',
        'tgl_kembali',
        'denda',
        'total_sewa',
        // 'bayar',
        // 'kembalian'
    ];

    public function mobil()
    {
        return $this->belongsTo(DataMobil::class, 'mobil_id');
    }
}
