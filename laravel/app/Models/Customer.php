<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'identitas',
        'umur',
        'no_hp',
        'alamat',
    ];

    public function transaksis(){
        return $this->hasMany(TransaksiPenyewaan::class);
    }
}
