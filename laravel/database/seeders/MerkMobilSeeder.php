<?php

namespace Database\Seeders;

use App\Models\MerkMobil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerkMobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MerkMobil::create([
            'nama' => 'Honda',
        ]);
    }
}
