<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImmunizationRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Find the infant IDs and corresponding barangay IDs
        $infantLourdesNorth = DB::table('infants')->where('name', 'John Doe')->first();
        $infantNinoyAquino = DB::table('infants')->where('name', 'Jane Smith')->first();
        $infantSalapungan = DB::table('infants')->where('name', 'James Johnson')->first();

        // Find the health worker IDs for each barangay
        $healthWorkerLourdesNorth = DB::table('users')->where('username', 'worker_Lourdes North')->first();
        $healthWorkerNinoyAquino = DB::table('users')->where('username', 'worker_Ninoy Aquino')->first();
        $healthWorkerSalapungan = DB::table('users')->where('username', 'worker_Salapungan')->first();

        // Insert the immunization records with their corresponding infant, barangay, and health worker IDs
        DB::table('immunization_records')->insert([
            // Immunization for Infant in Lourdes North
            [
                'infant_id' => $infantLourdesNorth->id,
                'barangay_id' => $infantLourdesNorth->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 1,
                'immunization_date' => '2023-02-20',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Lourdes North', // Changed to string
            ],
            [
                'infant_id' => $infantLourdesNorth->id,
                'barangay_id' => $infantLourdesNorth->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 2,
                'immunization_date' => '2023-03-20',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Lourdes North', // Changed to string
            ],
            [
                'infant_id' => $infantLourdesNorth->id,
                'barangay_id' => $infantLourdesNorth->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 3,
                'immunization_date' => '2023-04-20',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Lourdes North', // Changed to string
            ],

            // Immunization for Infant in Ninoy Aquino
            [
                'infant_id' => $infantNinoyAquino->id,
                'barangay_id' => $infantNinoyAquino->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 1,
                'immunization_date' => '2023-02-25',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Ninoy Aquino', // Changed to string
            ],
            [
                'infant_id' => $infantNinoyAquino->id,
                'barangay_id' => $infantNinoyAquino->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 2,
                'immunization_date' => '2023-03-25',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Ninoy Aquino', // Changed to string
            ],
            [
                'infant_id' => $infantNinoyAquino->id,
                'barangay_id' => $infantNinoyAquino->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 3,
                'immunization_date' => '2023-04-25',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Ninoy Aquino', // Changed to string
            ],

            // Immunization for Infant in Salapungan
            [
                'infant_id' => $infantSalapungan->id,
                'barangay_id' => $infantSalapungan->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 1,
                'immunization_date' => '2023-03-10',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Salapungan', // Changed to string
            ],
            [
                'infant_id' => $infantSalapungan->id,
                'barangay_id' => $infantSalapungan->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 2,
                'immunization_date' => '2023-04-10',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Salapungan', // Changed to string
            ],
            [
                'infant_id' => $infantSalapungan->id,
                'barangay_id' => $infantSalapungan->barangay_id,
                'vaccine_id' => 4,
                'dose_number' => 3,
                'immunization_date' => '2023-05-10',
                'remarks' => 'Good',
                'administered_by' => 'Health Care Worker Salapungan', // Changed to string
            ],
        ]);
    }
}
