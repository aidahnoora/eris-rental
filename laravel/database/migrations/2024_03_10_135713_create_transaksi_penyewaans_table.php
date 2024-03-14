<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_penyewaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobil_id');
            $table->dateTime('tgl_sewa');
            $table->dateTime('tgl_kembali');
            $table->double('denda');
            $table->double('total_sewa');
            // $table->double('bayar');
            // $table->double('kembalian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penyewaans');
    }
};
