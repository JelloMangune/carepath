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
        return response()->json(['data' => $vaccines], 200);
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

        return response()->json(['data' => $vaccine], 200);
    }

    // ... (Other methods)

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
        return response()->json(['data' => ['message' => 'Vaccine deleted']], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        // Check if the authenticated user has admin privileges (user_type = 0)
        if (Auth::user()->user_type !== 0) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $vaccine = Vaccine::find($id);

        if (!$vaccine) {
            return response()->json(['error' => 'Vaccine not found'], 404);
        }

        // Validate the request data
        $request->validate([
            'status' => 'required|in:0,1,2', // Assuming status values are 1 (Active) and 2 (Inactive)
        ]);

        // Update the vaccine status
        $vaccine->status = $request->input('status');
        $vaccine->save();

        return response()->json(['data' => ['message' => 'Vaccine status updated']], 200);
    }
}
