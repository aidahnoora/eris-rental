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
        Schema::create('data_mobils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merk_id')->constrained('merk_mobils')->onDelete('cascade');
            $table->string('nama');
            $table->string('kode');
            $table->string('model');
            $table->string('tahun_buat');
            $table->string('nomor_plat');
            $table->string('kapasitas');
            $table->string('bbm');
            $table->string('harga');
            $table->string('transmisi');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_mobils');
    }
};
