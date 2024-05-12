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
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('user_id');
            $table->string('nota');
            $table->date('tgl_sewa');
            $table->date('tgl_kembali');
            $table->integer('durasi_sewa');
            $table->date('tgl_pengembalian_mobil')->nullable();
            $table->enum('status', ['0', '1'])->default('0');
            $table->integer('lama_telat')->nullable()->default(0);
            $table->integer('denda_per_hari')->nullable()->default(0);
            $table->integer('total_denda')->nullable()->default(0);
            $table->integer('total_bayar');
            $table->text('perjanjian')->nullable();
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
