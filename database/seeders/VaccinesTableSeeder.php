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
            ['name' => 'BCG', 'short_name' => 'BCG'],
            ['name' => 'Hepatitis B (Hepa B)', 'short_name' => 'Hepa B'],
            ['name' => 'Pentavalent Vaccine (DPT-Hep-B-HiB)', 'short_name' => 'Pentavalent'],
            ['name' => 'Oral Polio Vaccine (OPV)', 'short_name' => 'OPV'],
            ['name' => 'Inactivated Polio Vaccine (IPV)', 'short_name' => 'IPV'],
            ['name' => 'Pneumococcal Conjugate Vaccine (PCV)', 'short_name' => 'PCV'],
            ['name' => 'Measles, Mumps, Rubella (MMR)', 'short_name' => 'MMR'],
        ]);
    }

}
