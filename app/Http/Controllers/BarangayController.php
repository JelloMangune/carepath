<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangay;

class BarangayController extends Controller
{
    /**
     * Fetch all barangays.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch()
    {
        $barangays = Barangay::all();
        return response()->json(['data' => $barangays]);
    }

    /**
     * Get all active barangays.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barangays = Barangay::where('status', 1)->get();
        return response()->json(['data' => $barangays]);
    }

    /**
     * Get details of a specific barangay.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barangay = Barangay::findOrFail($id);
        return response()->json(['data' => $barangay]);
    }

    /**
     * Store a new barangay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:barangays,name',
            // Add other validation rules as needed
        ]);

        $barangay = Barangay::create($request->all());
        return response()->json(['message' => 'Barangay created successfully', 'data' => $barangay], 201);
    }

    /**
     * Update a barangay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $barangay = Barangay::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:barangays,name,' . $barangay->id,
            // Add other validation rules as needed
        ]);

        $barangay->update($request->all());
        return response()->json(['message' => 'Barangay updated successfully', 'data' => $barangay]);
    }

    /**
     * Delete a barangay.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barangay = Barangay::findOrFail($id);
        $barangay->delete();
        return response()->json(['message' => 'Barangay deleted successfully']);
    }

    /**
     * Update the status of a barangay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $barangay = Barangay::findOrFail($id);

        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $barangay->update(['status' => $request->input('status')]);

        return response()->json(['message' => 'Barangay status updated successfully', 'data' => $barangay]);
    }

    
}
