<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('barangays')->insert([
            ['name' => 'Lourdes North', 'status' => 1, 'location' => 'Lourdes North'],
            ['name' => 'Ninoy Aquino', 'status' => 1, 'location' => 'Ninoy Aquino'],
            ['name' => 'Salapungan', 'status' => 1, 'location' => 'Salapungan'],
        ]);
    }
}
