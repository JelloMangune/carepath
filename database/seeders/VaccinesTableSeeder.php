<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class VaccinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('vaccines')->insert([
            ['name' => 'BCG', 'short_name' => 'BCG', 'status' => 1],
            ['name' => 'Hepatitis B (Hepa B)', 'short_name' => 'Hepa B', 'status' => 1],
            ['name' => 'Pentavalent Vaccine (DPT-Hep-B-HiB)', 'short_name' => 'Pentavalent', 'status' => 1],
            ['name' => 'Oral Polio Vaccine (OPV)', 'short_name' => 'OPV', 'status' => 1],
            ['name' => 'Inactivated Polio Vaccine (IPV)', 'short_name' => 'IPV', 'status' => 1],
            ['name' => 'Pneumococcal Conjugate Vaccine (PCV)', 'short_name' => 'PCV', 'status' => 1],
            ['name' => 'Measles, Mumps, Rubella (MMR)', 'short_name' => 'MMR', 'status' => 1],
        ]);
    }

}
