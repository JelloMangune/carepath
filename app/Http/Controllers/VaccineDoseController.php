<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VaccineDose;
use Illuminate\Support\Facades\Auth;

class VaccineDoseController extends Controller
{
    /**
     * Get all vaccine doses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all vaccine doses
        $vaccineDoses = VaccineDose::all();

        return response()->json(['data' => $vaccineDoses], 200);
    }

    /**
     * Get a single vaccine dose by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the vaccine by ID
        $vaccine = VaccineDose::find($id);

        if (!$vaccine) {
            return response()->json(['error' => 'Vaccine not found'], 404);
        }

        // Get all the doses associated with the vaccine
        $vaccineDoses = VaccineDose::where('vaccine_id', $vaccine->id)->get();

        if ($vaccineDoses->isEmpty()) {
            return response()->json(['error' => 'No doses found for this vaccine'], 404);
        }

        // Create an array to store dose information including IDs
        $doseInfo = [];

        foreach ($vaccineDoses as $dose) {
            $doseInfo[] = [
                'id' => $dose->id, // Include the dose ID
                'dose_number' => $dose->dose_number,
                'months_to_take' => $dose->months_to_take,
            ];
        }

        return response()->json(['data' => $doseInfo], 200);
    }


    /**
     * Store a new vaccine dose (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if the authenticated user has admin privileges (user_type = 0)
        if (Auth::user()->user_type !== 0) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate the request data
        $request->validate([
            'vaccine_id' => 'required|exists:vaccines,id',
            'dose_number' => 'required|integer',
            'months_to_take' => 'required|numeric',
            // Add other validation rules as needed
        ]);

        // Create a new vaccine dose
        $vaccineDose = VaccineDose::create($request->all());

        return response()->json(['message' => 'Vaccine dose created successfully', 'data' => $vaccineDose], 201);
    }

    /**
     * Update a vaccine dose by ID (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check if the authenticated user has admin privileges (user_type = 0)
        if (Auth::user()->user_type !== 0) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the vaccine dose by ID
        $vaccineDose = VaccineDose::find($id);

        if (!$vaccineDose) {
            return response()->json(['error' => 'Vaccine dose not found'], 404);
        }

        // Validate the request data
        $request->validate([
            'vaccine_id' => 'required|exists:vaccines,id',
            'dose_number' => 'required|integer',
            'months_to_take' => 'required|numeric',
            // Add other validation rules as needed
        ]);

        // Update the vaccine dose's attributes
        $vaccineDose->update($request->all());

        return response()->json(['message' => 'Vaccine dose updated successfully', 'data' => $vaccineDose], 200);
    }

    /**
     * Delete a vaccine dose by ID (admin only).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check if the authenticated user has admin privileges (user_type = 0)
        if (Auth::user()->user_type !== 0) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the vaccine dose by ID
        $vaccineDose = VaccineDose::find($id);

        if (!$vaccineDose) {
            return response()->json(['error' => 'Vaccine dose not found'], 404);
        }

        // Delete the vaccine dose
        $vaccineDose->delete();

        return response()->json(['message' => 'Vaccine dose deleted successfully']);
    }
}
