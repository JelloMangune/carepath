<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;
use Illuminate\Support\Facades\Auth;

class VaccineController extends Controller
{
    /**
     * Get all vaccines.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vaccines = Vaccine::all();
        return response()->json($vaccines, 200);
    }

    /**
     * Get a single vaccine by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vaccine = Vaccine::find($id);

        if (!$vaccine) {
            return response()->json(['error' => 'Vaccine not found'], 404);
        }

        return response()->json($vaccine, 200);
    }

    /**
     * Create a new vaccine (admin only).
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

        $request->validate([
            'name' => 'required|string',
            'short_name' => 'nullable|string',
        ]);

        $vaccine = Vaccine::create($request->all());
        return response()->json($vaccine, 201);
    }

    /**
     * Update a vaccine by ID (admin only).
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

        $vaccine = Vaccine::find($id);

        if (!$vaccine) {
            return response()->json(['error' => 'Vaccine not found'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'short_name' => 'nullable|string',
        ]);

        $vaccine->update($request->all());
        return response()->json($vaccine, 200);
    }

    /**
     * Delete a vaccine by ID (admin only).
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

        $vaccine = Vaccine::find($id);

        if (!$vaccine) {
            return response()->json(['error' => 'Vaccine not found'], 404);
        }

        $vaccine->delete();
        return response()->json(['message' => 'Vaccine deleted'], 200);
    }
}
