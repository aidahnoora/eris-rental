<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'identitas',
        'jaminan',
        'umur',
        'no_hp',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function transaksis(){
        return $this->hasMany(TransaksiPenyewaan::class);
    }
}
