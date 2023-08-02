<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImmunizationRecord;

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
            // Define your validation rules here
        ]);

        $record = ImmunizationRecord::create($request->all());
        return response()->json(['message' => 'Immunization record created successfully', 'data' => $record], 201);
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
            return response()->json(['error' => 'Immunization record not found'], 404);
        }

        $record->delete();

        return response()->json(['message' => 'Immunization record deleted successfully']);
    }
}
