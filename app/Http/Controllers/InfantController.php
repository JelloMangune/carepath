<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infant;

class InfantController extends Controller
{
    /**
     * Get all infants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all infants
        $infants = Infant::all();

        return response()->json(['data' => $infants], 200);
    }

    /**
     * Get a single infant by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['error' => 'Infant not found'], 404);
        }

        return response()->json(['data' => $infant], 200);
    }

    /**
     * Store a new infant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        // Generate a unique tracking number
        $trackingNumber = $this->generateUniqueTrackingNumber();

        // Create the infant with the generated tracking number
        $infant = Infant::create(array_merge($request->all(), ['tracking_number' => $trackingNumber]));

        return response()->json(['message' => 'Infant created successfully', 'data' => $infant], 201);
    }

    /**
     * Update an infant by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['data' => null, 'error' => 'Infant not found'], 404);
        }

        // Update the infant's attributes
        $infant->update($request->all());

        return response()->json(['data' => ['message' => 'Infant updated successfully', 'infant' => $infant]], 200);
    }

    /**
     * Delete an infant by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['data' => ['error' => 'Infant not found'], 'message' => null], 404);
        }

        // Delete the infant
        $infant->delete();

        return response()->json(['data' => ['message' => 'Infant deleted successfully'], 'error' => null]);
    }

    /**
     * Generate a unique tracking number.
     *
     * @return string
     */
    private function generateUniqueTrackingNumber()
    {
        $trackingNumber = mt_rand(1000000000, 9999999999); // Generate a 10-digit number

        // Check if the generated tracking number already exists in the database
        while (Infant::where('tracking_number', $trackingNumber)->exists()) {
            $trackingNumber = mt_rand(1000000000, 9999999999);
        }

        return $trackingNumber;
    }

    public function getFilteredInfants($barangay_id, $year = null)
    {
        // Create a query builder for infants
        $query = Infant::query();

        // If a specific barangay_id is provided, filter infants by barangay
        if ($barangay_id != 0) {
            $query->where('barangay_id', $barangay_id);
        }

        // If a year is provided, add a filter for the birth year
        if (!is_null($year)) {
            // Extract the year part from the birth_date column and compare it
            $query->whereYear('birth_date', '=', $year);
        }

        // Fetch infants based on the filters
        $infants = $query->get();

        return response()->json(['data' => $infants], 200);
    }

    public function viewImmunizationHistory($id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['error' => 'Infant not found'], 404);
        }

        // Get the immunization records for the infant
        $immunizationRecords = $infant->immunizationRecords;

        // Prepare the response data
        $responseData = [
            'Infant name' => $infant->name,
            'Immunization history' => [],
        ];

        // Iterate through immunization records and format the data
        foreach ($immunizationRecords as $record) {
            $administeredByName = $record->administered_by ? $record->administered_by : 'Unknown User';
        
            $formattedRecord = [
                'Id' => $record->id,
                'Immunization Date' => $record->immunization_date,
                'Vaccine Name' => $record->vaccine->name,
                'Vaccination Location' => $record->barangay_id,
                'Vaccine Dose' => 'Dose ' . $record->dose_number,
                'Administered By' => $administeredByName,
                'Remarks' => $record->remarks,
            ];
        
            $responseData['Immunization history'][] = $formattedRecord;
        }
        
        return response()->json(['data' => $responseData], 200);
    }

}
