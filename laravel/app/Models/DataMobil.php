<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMobil extends Model
{
    use HasFactory;

    protected $fillable = [
        'merk_id',
        'nama',
        'kode',
        'model',
        'tahun_buat',
        'nomor_plat',
        'kapasitas',
        'bbm',
        'harga_sewa',
        'transmisi',
        'image',
    ];

    public function merk()
    {
        return $this->belongsTo(MerkMobil::class, 'merk_id');
    }

    public function transaksis(){
        return $this->hasMany(TransaksiPenyewaan::class);
    }
}
