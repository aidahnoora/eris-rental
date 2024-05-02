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
        Schema::table('data_mobils', function (Blueprint $table) {
            $table->after('nama', function ($table) {
                $table->string('stok')->nullable()->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_mobils', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
};
