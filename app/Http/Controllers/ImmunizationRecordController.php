<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImmunizationRecord;
use App\Models\Infant;

class ImmunizationRecordController extends Controller
{
    /**
     * Get all immunization records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = ImmunizationRecord::all();
        return response()->json(['data' => $records], 200);
    }

    /**
     * Get a single immunization record by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = ImmunizationRecord::find($id);

        if (!$record) {
            return response()->json(['error' => 'Immunization record not found'], 404);
        }

        return response()->json(['data' => $record], 200);
    }

    /**
     * Store a new immunization record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'infant_id' => 'required|integer',
            'vaccine_id' => 'required|integer',
            'dose_number' => 'required|integer',
        ]);

        // Check if a record with the same infant, vaccine_id, and dose_number exists
        $existingRecord = ImmunizationRecord::where([
            'infant_id' => $request->input('infant_id'),
            'vaccine_id' => $request->input('vaccine_id'),
            'dose_number' => $request->input('dose_number'),
        ])->first();

        if ($existingRecord) {
            // If a matching record exists, return an error response
            return response()->json([
                'error' => 'Infant already taken the vaccine',
                'data' => $existingRecord,
            ]);
        }

        // Create a new record if no matching record is found
        $record = ImmunizationRecord::create($request->all());

        // Update the infant's status to 1
        Infant::where('id', $request->input('infant_id'))->update(['status' => 1]);

        return response()->json([
            'message' => 'Immunization record created successfully',
            'data' => $record,
        ]);
    }

    /**
     * Update an immunization record by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $record = ImmunizationRecord::find($id);

        if (!$record) {
            return response()->json(['error' => 'Immunization record not found'], 404);
        }

        $request->validate([
            // Define your validation rules here
        ]);

        $record->update($request->all());

        return response()->json(['message' => 'Immunization record updated successfully', 'data' => $record], 200);
    }

    /**
     * Delete an immunization record by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = ImmunizationRecord::find($id);

        if (!$record) {
            return response()->json(['data' => ['error' => 'Immunization record not found'], 'message' => null], 404);
        }

        $record->delete();
        return response()->json(['data' => ['message' => 'Immunization record deleted successfully'], 'error' => null]);
    }


    public function getFilteredImmunizationRecords($barangay_id, $year = null)
    {
        try {
            // Create a query builder for immunization records
            $query = ImmunizationRecord::query();

            // If a specific barangay_id is provided and it's not 0, filter records by barangay
            if ($barangay_id !== '0') {
                $query->where('barangay_id', $barangay_id);
            }

            // If a year is provided and it's not null, add a filter for the year
            if (!is_null($year)) {
                $query->whereYear('immunization_date', '=', $year);
            }

            // Eager load the related Infant and Vaccine models and select the columns you need
            $query->with(['infant:id,name', 'vaccine:id,name']); // Adjust the columns as needed
            $query->orderBy('immunization_date', 'desc');
            // Fetch immunization records based on the filters
            $immunizationRecords = $query->get();

            // Check if any records were found
            if ($immunizationRecords->isEmpty()) {
                return response()->json(['error' => 'No records found'], 404);
            }

            // Format the response to include infant_name and vaccine_name
            $formattedRecords = $immunizationRecords->map(function ($record) {
                return [
                    'id' => $record->id,
                    'infant_name' => $record->infant->name,
                    'vaccine_name' => $record->vaccine->name,
                    'dose_number' => $record->dose_number,
                    'barangay_id' => $record->barangay_id,
                    'immunization_date' => $record->immunization_date,
                    'administered_by' => $record->administered_by,
                    'remarks' => $record->remarks,
                ];
            });

            return response()->json(['data' => $formattedRecords], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error in server'], 500);
        }
    }



}
