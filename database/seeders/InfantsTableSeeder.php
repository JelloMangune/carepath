<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Find the barangay IDs
        $lourdesNorthBarangayId = DB::table('barangays')->where('name', 'Lourdes North')->value('id');
        $ninoyAquinoBarangayId = DB::table('barangays')->where('name', 'Ninoy Aquino')->value('id');
        $salapunganBarangayId = DB::table('barangays')->where('name', 'Salapungan')->value('id');

        // Insert the infants with their corresponding barangay IDs
        DB::table('infants')->insert([
            // Infant in Lourdes North
            [
                'name' => 'John Doe',
                'tracking_number' => '1234567890', // Replace with actual tracking number
                'sex' => 'Male',
                'birth_date' => '2023-01-15',
                'family_serial_number' => 'FSN12345',
                'barangay_id' => $lourdesNorthBarangayId,
                'weight' => 3.5,
                'length' => 50,
                'father_name' => 'Michael Doe',
                'mother_name' => 'Jennifer Doe',
                'contact_number' => '1234567890',
                'complete_address' => '123 Main Street, City',
                'status' => 1, // Not vaccinated
            ],

            // Infant in Ninoy Aquino
            [
                'name' => 'Jane Smith',
                'tracking_number' => '9876543210', // Replace with actual tracking number
                'sex' => 'Female',
                'birth_date' => '2023-02-20',
                'family_serial_number' => 'FSN67890',
                'barangay_id' => $ninoyAquinoBarangayId,
                'weight' => 3.2,
                'length' => 48,
                'father_name' => 'John Smith',
                'mother_name' => 'Mary Smith',
                'contact_number' => '9876543210',
                'complete_address' => '456 Oak Avenue, Town',
                'status' => 1, // Partially vaccinated
            ],

            // Infant in Salapungan
            [
                'name' => 'James Johnson',
                'tracking_number' => '5432167890', // Replace with actual tracking number
                'sex' => 'Male',
                'birth_date' => '2023-03-10',
                'family_serial_number' => 'FSN24680',
                'barangay_id' => $salapunganBarangayId,
                'weight' => 3.8,
                'length' => 51,
                'father_name' => 'Robert Johnson',
                'mother_name' => 'Emily Johnson',
                'contact_number' => '5432167890',
                'complete_address' => '789 Elm Street, Village',
                'status' => 1, // Fully vaccinated
            ],
        ]);
    }
}
