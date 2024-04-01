<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'nama' => 'Eris Greyrat',
            'identitas' => 'SIM',
            'umur' => '26',
            'no_hp' => '097758900123',
            'alamat' => 'Surabaya',
        ]);
    }
}
