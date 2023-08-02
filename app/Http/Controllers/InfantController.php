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
            'birthdate' => 'required|date',
            // Add other validation rules as needed
        ]);

        $infant = Infant::create($request->all());
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
            return response()->json(['error' => 'Infant not found'], 404);
        }

        // Update the infant's attributes
        $infant->update($request->all());

        return response()->json(['message' => 'Infant updated successfully', 'data' => $infant], 200);
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
            return response()->json(['error' => 'Infant not found'], 404);
        }

        // Delete the infant
        $infant->delete();

        return response()->json(['message' => 'Infant deleted successfully']);
    }
}
