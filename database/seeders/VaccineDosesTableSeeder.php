<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaccineDosesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $vaccines = [
            'BCG' => ['1' => 0],
            'Hepatitis B (Hepa B)' => ['1' => 0],
            'Pentavalent Vaccine (DPT-Hep-B-HiB)' => [
                '1' => 1.5,
                '2' => 2.5,
                '3' => 3.5,
            ],
            'Oral Polio Vaccine (OPV)' => [
                '1' => 1.5,
                '2' => 2.5,
                '3' => 3.5,
            ],
            'Inactivated Polio Vaccine (IPV)' => ['1' => 3.5],
            'Pneumococcal Conjugate Vaccine (PCV)' => [
                '1' => 1.5,
                '2' => 2.5,
                '3' => 3.5,
            ],
            'Measles, Mumps, Rubella (MMR)' => [
                '1' => 9,
                '2' => 12,
            ],
        ];

        foreach ($vaccines as $vaccineName => $doses) {
            $vaccine = DB::table('vaccines')->where('name', $vaccineName)->first();

            foreach ($doses as $doseNumber => $monthsToTake) {
                DB::table('vaccine_doses')->insert([
                    'vaccine_id' => $vaccine->id,
                    'dose_number' => $doseNumber,
                    'months_to_take' => $monthsToTake,
                ]);
            }
        }
    }
}