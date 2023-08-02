<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangay;

class BarangayController extends Controller
{
    public function index()
    {
        $barangays = Barangay::all();
        return response()->json(['data' => $barangays]);
    }

    public function show($id)
    {
        $barangay = Barangay::findOrFail($id);
        return response()->json(['data' => $barangay]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:barangays,name',
            // Add other validation rules as needed
        ]);

        $barangay = Barangay::create($request->all());
        return response()->json(['message' => 'Barangay created successfully', 'data' => $barangay], 201);
    }

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

    public function destroy($id)
    {
        $barangay = Barangay::findOrFail($id);
        $barangay->delete();
        return response()->json(['message' => 'Barangay deleted successfully']);
    }
}
